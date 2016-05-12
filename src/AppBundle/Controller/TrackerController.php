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
     * @Route("/trackers-time/{id}", name="trackers-time")
     * @param integer $id
     * @Template()
     * @return array
     */
    public function trackersTimeAction($id, Request $request)
    {
        $client = new Redmine\Client('https://redmine.ekreative.com', 'test', '9uu82T487m6V41G');
        $res = [];
        
        $trackersTime = $client->api('time_entry')->all();

        foreach ($trackersTime['time_entries'] as $value){
            if( $value['project']['id'] == $id){
                $res[] = $value;
            }
        }

        return ['trackers_time' => $res];
    }

    /**
     * @Route("/trackers-time-issue/{id}", name="trackers-time-issue")
     * @param integer $id
     * @Template()
     * @return array
     */

    public function trackersTimeIssueAction($id, Request $request)
    {
        $client = new Redmine\Client('https://redmine.ekreative.com', 'test', '9uu82T487m6V41G');
        $res = [];

        $trackersTime = $client->api('time_entry')->all();

        foreach ($trackersTime['time_entries'] as $value){
            if( isset($value['issue']) && $value['issue']['id'] == $id){
                $res[] = $value;
            }
        }

        return ['trackers_time' => $res];
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

    /**
     * @Route("/track-issue/{id}", name="track-issue")
     * @param integer $id
     * @Template()
     * @return array
     */
    public function trackIssueAction($id, Request $request)
    {
        $client = new Redmine\Client('https://redmine.ekreative.com', 'test', '9uu82T487m6V41G');



        $tracker = new Tracker();
        $form = $this->createForm(NewTracker::class, $tracker);
        $form->handleRequest($request);

        if($request->getMethod() == Request::METHOD_POST){
            if ($form->isValid()) {
                $client->api('time_entry')->create(array(
//                    'project_id'  => $id,
                     'issue_id' => $id,
                    // 'spent_on' => null,
                    'hours'       => $tracker->getHours(),
                    'activity_id' => $tracker->getActivityId(),
                    'comments'    => $tracker->getComments(),
                ));
                return $this->redirect($this->generateUrl('show-issue', array('id' => $id)));
            }
        }
        return ['form' => $form->createView()];
    }
}
