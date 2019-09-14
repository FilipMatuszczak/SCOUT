<?php

namespace App\Controller;

use App\Security\UserProvider;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Security;

/** */
class MainController extends AbstractController
{
    private $userProvider;

    private $security;

    public function __construct(UserProvider $userProvider, Security $security)
    {
        $this->userProvider = $userProvider;
        $this->security = $security;
    }

    /**
     * @return Response
     */
    public function mainAction()
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $userToken = $this->security->getUser();

        $user = $this->userProvider->loadUserByUsername($userToken->getUsername());
        if ($this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('admin_reports');
        }
        return $this->render('main/main.html.twig', ["projects" => $user->getProject()]);
    }
}