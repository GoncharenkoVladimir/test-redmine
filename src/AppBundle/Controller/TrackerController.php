<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Redmine;
use AppBundle\Form\NewTracker;
use AppBundle\Form\Model\Tracker;

class TrackerController extends Controller
{
    /**
     * @Route("/trackers-time", name="trackers-time")
     * @Template()
     */
    public function trackersTimeAction(Request $request)
    {
        $client = new Redmine\Client('https://redmine.ekreative.com', 'test', '9uu82T487m6V41G');
        
        $trackersTime = $client->api('time_entry')->all();
        
        return ['trackers_time' => $trackersTime];
    }

    /**
     * @Route("/track-project/{id}", name="track-project")
     * @param integer $id
     * @Template()
     * @return array
     */
    public function trackProjectAction($id, Request $request)
    {
        $client = new Redmine\Client('https://redmine.ekreative.com', 'test', '9uu82T487m6V41G');

        

        $tracker = new Tracker();
        $form = $this->createForm(NewTracker::class, $tracker);
        $form->handleRequest($request);

        if($request->getMethod() == Request::METHOD_POST){
            if ($form->isValid()) {
                $client->api('time_entry')->create(array(
                    'project_id'  => $id,
                    // 'issue_id' => 140,
                    // 'spent_on' => null,
                    'hours'       => $tracker->getHours(),
                    'activity_id' => $tracker->getActivityId(),
                    'comments'    => $tracker->getComments(),
                ));
                return $this->redirect($this->generateUrl('project', array('id' => $id)));
            }
        }
        return ['form' => $form->createView()];
    }
}
