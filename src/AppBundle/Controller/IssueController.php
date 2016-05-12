<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Redmine;
use AppBundle\Form\NewTracker;
use AppBundle\Form\Model\Tracker;

class IssueController extends Controller
{
    /**
     * @Route("/issue/{id}", name="show-issue")
     * @param string $id
     * @Template()
     * @return array
     */
    public function showIssueAction($id, Request $request)
    {
        $client = new Redmine\Client('https://redmine.ekreative.com', 'test', '9uu82T487m6V41G');

        $issue = $client->api('issue')->show($id);
        
        return ['issue' => $issue];
    }


}
