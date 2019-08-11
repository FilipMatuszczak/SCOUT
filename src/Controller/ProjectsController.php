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

    public function indexAction(Request $request)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $sorting = !empty($request->get('sorting')) ? $request->get('sorting') : 'A-Z';
        $page = !empty($request->get('page')) ? $request->get('page') : 1;
        $technology = !empty($request->get('technology')) ? $request->get('technology') : '';
        $title = !empty($request->get('title')) ? $request->get('title') : '';
        $member = !empty($request->get('member')) ? $request->get('member') : '';

        $projects = $this->projectDataProvider->getProjectsByFilters($sorting, $page, $technology, $title, $member);

        return $this->render('main/search_projects.html.twig', [
            'projects' => $projects,
            'page' => $page,
            'sorting' => $sorting,
            'technology' => $technology,
            'title' => $title,
            'member' => $member
        ]);
    }
}