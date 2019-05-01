<?php

namespace App\Controller;

use App\Entity\User;
use App\Services\MailerService;
use App\Services\RegisterHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/** */
class LoginController extends AbstractController
{
    /** @var RegisterHandler */
    private $registerHandler;

    /** @var MailerService */
    private $mailerService;

    /**
     * @param RegisterHandler $registerHandler
     * @param MailerService $mailerService
     */
    public function __construct(RegisterHandler $registerHandler, MailerService $mailerService)
    {
        $this->registerHandler = $registerHandler;
        $this->mailerService = $mailerService;
    }

    /** */
    public function indexAction()
    {
        return $this->render('main/main.html.twig', []);
    }

    /**
     * @param $username
     * @param $authenticationLink
     *
     * @return Response
     */
    public function authenticateUserAction($username, $authenticationLink)
    {
        $user = $this->mailerService->verifyAuthenticationLink($username, $authenticationLink);

        if (!$user) {
            throw $this->createNotFoundException(
                'Cannot verify user '. $username
            );
        }

        return $this->render('main/verify_user.html.twig', ['name' => $username]);
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function createUserAction(Request $request)
    {
        $newUserData = json_decode(
            $request->getContent(),
            true
        );

        $user = $this->registerHandler->registerUser($newUserData);

        return $this->forward('App\Controller\MailController::sendRegisterMailAction', [
            User::COLUMN_EMAIL => $user->getEmail(),
            User::COLUMN_USERNAME => $user->getUsername(),
            'authenticationLink' => $user->getAuthenticationLink(),
        ]);
    }
}