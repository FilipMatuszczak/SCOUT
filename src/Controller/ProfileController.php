<?php


namespace App\Controller;

use App\Repository\PostRepository;
use App\Security\UserProvider;
use App\Services\PostCreator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;

class ProfileController extends AbstractController
{
    /** @var UserProvider */
    private $userProvider;

    /** @var PostCreator */
    private $postCreator;

    /** @var Security */
    private $security;

    /** @var PostRepository */
    private $postRepository;

    public function __construct(UserProvider $userProvider, PostCreator $postCreator, Security $security, PostRepository $postRepository)
    {
        $this->userProvider = $userProvider;
        $this->postCreator = $postCreator;
        $this->security = $security;
        $this->postRepository = $postRepository;
    }

    public function profileIndexAction($username)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $user = $this->userProvider->loadUserByUsername($username);
        $userAge = date_diff(date_create($user->getDateOfBirth()->format('Y-m-d H:i:s')), date_create('today'))->y;
        $posts = $this->postRepository->fetchUserWallPosts($user);

        return $this->render('main/profile.html.twig', ['user' => $user, 'age' => $userAge, 'posts' => $posts]);
    }

    public function createPostOnWall(Request $request)
    {
        $username = $user = $this->security->getUser()->getUsername();

        $user = $this->userProvider->loadUserByUsername($username);

        $this->postCreator->createPostForUser($user, $request->get('text'));

        return $this->redirectToRoute('user_profile', ['username' => $request->get('username')]);
    }
}