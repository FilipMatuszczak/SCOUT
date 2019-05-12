<?php

namespace App\Controller;

use App\Entity\User;
use App\Services\MailerService;
use App\Services\RegisterHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
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
        ////return $this->render('main/main.html.twig', []);
        return $this->render('main/index.html.twig', []); //testuje

    }

    public function userExistsAction(Request $request)
    {

        $username = $request->get('username');

        $userExists = $this->registerHandler->userExists($username);

        return new JsonResponse(
            [
                'user_exists' => $userExists,
            ]
        );

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
                'Cannot verify user ' . $username
            );
        }

        return $this->render('main/activate-web.html.twig', ['name' => $username]);
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function createUserAction(Request $request)
    {
        $newUserData = [
            User::COLUMN_USERNAME => $request->get('username'),
            User::COLUMN_FIRST_NAME => $request->get('firstname'),
            User::COLUMN_LAST_NAME => $request->get('lastname'),
            User::COLUMN_EMAIL => $request->get('email'),
            User::COLUMN_PASSWORD => $request->get('password'),
        ];

        $user = $this->registerHandler->registerUser($newUserData);

        return $this->forward('App\Controller\MailController::sendRegisterMailAction', [
            User::COLUMN_EMAIL => $user->getEmail(),
            User::COLUMN_USERNAME => $user->getUsername(),
            'authenticationLink' => $user->getAuthenticationLink(),
        ]);
    }

    public function emailConfirmationSentAction()
    {
        return $this->render('main/registered-web.html.twig', []);
    }
}