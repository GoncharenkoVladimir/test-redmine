<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Redmine;
use AppBundle\Form\NewProject;
use AppBundle\Form\Model\Project;

class ProjectController extends Controller
{
    /**
     * @Route("/all-projects", name="all-projects")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $client = new Redmine\Client('https://redmine.ekreative.com', 'test', '9uu82T487m6V41G');

        $projects = $client->api('project')->all(array(
            'limit' => 10
        ));
        
        return ['projects' => $projects];
    }
    /**
     * @Route("/create-project", name="create-project")
     * @Template()
     */
    public function createProjectAction(Request $request)
    {
        $client = new Redmine\Client('https://redmine.ekreative.com', 'test', '9uu82T487m6V41G');

        $project = new Project();
        $form = $this->createForm(NewProject::class, $project);
        $form->handleRequest($request);

        if($request->getMethod() == Request::METHOD_POST){
            if ($form->isValid()) {
                    $client->api('project')->create(array(
                        'name'        => $project->getName(),
                        'identifier'  => $project->getIdentifier(),
                        'description'  => $project->getDescription(),
                        'tracker_ids' => array(),
                    ));
                return $this->redirect($this->generateUrl('all-projects'));
            }
        }
        return ['form' => $form->createView()];
    }

    /**
     * @Route("/project/{id}", name="project")
     * @param integer $id
     * @Template()
     * @return array
     */
    public function showProjectAction($id, Request $request)
    {
        $client = new Redmine\Client('https://redmine.ekreative.com', 'test', '9uu82T487m6V41G');
        $project = $client->api('project')->show($id);
        $issues = $client->api('issue')->all(array('project_id'     => $id));

        return ['project' => $project, 'issues' => $issues];
    }
    /**
     * @Route("/remove-project", name="remove-project")
     * @Template()
     */
    public function removeProjectAction(Request $request)
    {
        $client = new Redmine\Client('https://redmine.ekreative.com', 'test', '9uu82T487m6V41G');

        return [];
    }
}
