<?php


namespace App\Controller;


use App\Security\UserProvider;
use App\Services\ProjectCreator;
use App\Services\ProjectsDataProvider;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;

class ProjectsController extends AbstractController
{
    /** @var ProjectsDataProvider */
    private $projectDataProvider;

    /** @var ProjectCreator */
    private $projectCreator;

    /** @var Security */
    private $security;

    /** @var UserProvider */
    private $userProvider;

    public function __construct(
        ProjectsDataProvider $projectDataProvider,
        ProjectCreator $projectCreator,
        Security $security,
        UserProvider $userProvider
    )
    {
        $this->projectDataProvider = $projectDataProvider;
        $this->projectCreator = $projectCreator;
        $this->security = $security;
        $this->userProvider = $userProvider;
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
        $nextProjects = $this->projectDataProvider->getProjectsByFilters($sorting, $page+1, $technology, $title, $member);

        $nextPage = $page;
        if (sizeof($nextProjects) != 0){
            $nextPage = $page +1;
        }
        return $this->render('main/search_projects.html.twig', [
            'projects' => $projects,
            'page' => $page,
            'sorting' => $sorting,
            'technology' => $technology,
            'title' => $title,
            'member' => $member,
            'nextPage' => $nextPage,
        ]);
    }

    public function createProjectIndexAction()
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        return $this->render('main/new-project.html.twig');
    }

    public function createProjectAction(Request $request)
    {
        $username = $this->security->getUser()->getUsername();
        $user = $this->userProvider->loadUserByUsername($username);

        $title = $request->get('title');
        $description = $request->get('text');
        $photo = $request->files->get('image');
        $technologies = $request->get('technologies');

        $this->projectCreator->createProject($title, $description, $photo, $technologies, $user);

        return $this->redirectToRoute('main');
    }

    private function canEdit($username)
    {
        $currentUsername = $user = $this->security->getUser()->getUsername();

        if ($currentUsername !== $username) {
            return null;
        }

        return $this->userProvider->loadUserByUsername($username);
    }
}