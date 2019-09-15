<?php


namespace App\Controller;


use App\Security\UserProvider;
use App\Services\PostCreator;
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

    /** @var PostCreator */
    private $postCreator;

    public function __construct(
        ProjectsDataProvider $projectDataProvider,
        ProjectCreator $projectCreator,
        Security $security,
        UserProvider $userProvider,
        PostCreator $postCreator
    )
    {
        $this->projectDataProvider = $projectDataProvider;
        $this->projectCreator = $projectCreator;
        $this->security = $security;
        $this->userProvider = $userProvider;
        $this->postCreator = $postCreator;
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
        $nextProjects = $this->projectDataProvider->getProjectsByFilters($sorting, $page + 1, $technology, $title, $member);

        $nextPage = $page;
        if (sizeof($nextProjects) != 0) {
            $nextPage = $page + 1;
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

    public function projectProfileAction($projectId)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $userId = $this->userProvider->loadUserByUsername($this->security->getUser()->getUsername())->getUserId();
        $project = $this->projectDataProvider->getProjectById($projectId);
        $isAuthor = false;
        $isMember = false;
        $status = $this->projectDataProvider->getUserProjectStatus($userId, $projectId);

        if ($status === ProjectsDataProvider::USER_MEMBER) {
            $isMember = true;
        }

        if ($status === ProjectsDataProvider::USER_AUTHOR) {
            $isAuthor = true;
            $isMember = true;
        }

        $posts = $this->projectDataProvider->getProjectPosts($project);

        return $this->render("main/project.html.twig", ['project' => $project, 'isMember' => $isMember, 'isAuthor' => $isAuthor, 'posts' => $posts]);
    }

    public function createProjectIndexAction()
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        return $this->render('main/new-project.html.twig');
    }

    public function createProjectAction(Request $request)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $username = $this->security->getUser()->getUsername();
        $user = $this->userProvider->loadUserByUsername($username);

        $title = $request->get('title');
        $description = $request->get('text');
        $photo = $request->files->get('image');
        $technologies = $request->get('technologies');

        $this->projectCreator->createProject($title, $description, $photo, $technologies, $user);

        return $this->redirectToRoute('main');
    }

    public function createPostForProject(Request $request)
    {
        $username = $this->security->getUser()->getUsername();

        $user = $this->userProvider->loadUserByUsername($username);
        $photoFile = $request->files->get('photo');
        $text = $request->get('text');
        $project = $this->projectDataProvider->getProjectById($request->get('projectId'));

        $this->postCreator->createPostForProject($user, $text, $project, $photoFile);

        return $this->redirectToRoute('project_profile', ['projectId' => $project->getProjectId()]);
    }

    public function editProjectAction(Request $request)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $username = $this->security->getUser()->getUsername();
        $user = $this->userProvider->loadUserByUsername($username);

        $projectId = $request->get('projectId');
        $title = $request->get('title');
        $description = $request->get('text');
        $photo = $request->files->get('photo');
        $technologies = $request->get('technologies');

        $this->projectCreator->updateProject($projectId, $title, $description, $photo, $technologies, $user);

        return $this->redirectToRoute('project_profile', ['projectId' => $projectId]);
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