<?php


namespace App\Controller;


use App\Services\ProjectsDataProvider;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class ProjectsController extends AbstractController
{
    /** @var ProjectsDataProvider */
    private $projectDataProvider;

    public function __construct(ProjectsDataProvider $projectDataProvider)
    {
        $this->projectDataProvider = $projectDataProvider;
    }

    public function indexAction(Request $request, $page)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $sorting = $request->attributes->get('sorting');
        $dir = $request->attributes->get('dir');
        $title = $request->attributes->get('title');

        $projects = $this->projectDataProvider->getProjectsByFilters($sorting, $dir, $page-1, $title);

        return $this->render('main/search_projects.html.twig', ['projects' => $projects]);
    }
}