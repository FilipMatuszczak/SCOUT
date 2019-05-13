<?php

namespace App\Controller;

use App\Entity\User;
use App\Security\LoginFormAuthenticator;
use App\Security\UserProvider;
use App\Services\MailerService;
use App\Services\RegisterHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/** */
class LoginController extends AbstractController
{
    /** @var RegisterHandler */
    private $registerHandler;

    /** @var MailerService */
    private $mailerService;

    /** @var AuthenticationUtils */
    private $authenticationUtils;

    /** @var LoginFormAuthenticator */
    private $loginFormAuthenticator;

    /** @var UserProvider */
    private $userProvider;

    /**
     * @param RegisterHandler $registerHandler
     * @param MailerService $mailerService
     * @param AuthenticationUtils $authenticationUtils
     * @param LoginFormAuthenticator $loginFormAuthenticator
     * @param UserProvider $userProvider
     */
    public function __construct(
        RegisterHandler $registerHandler,
        MailerService $mailerService,
        AuthenticationUtils $authenticationUtils,
        LoginFormAuthenticator $loginFormAuthenticator,
        UserProvider $userProvider
    )
    {
        $this->registerHandler = $registerHandler;
        $this->mailerService = $mailerService;
        $this->authenticationUtils = $authenticationUtils;
        $this->loginFormAuthenticator = $loginFormAuthenticator;
        $this->userProvider = $userProvider;
    }

    /** */
    public function indexAction()
    {
        $error = $this->authenticationUtils->getLastAuthenticationError();
        $lastUsername = $this->authenticationUtils->getLastUsername();

        return $this->render('main/index.html.twig',  ['last_username' => $lastUsername, 'error' => $error]);
    }

    public function userExistsAction(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $username = $data['username'];

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

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function loginAction(Request $request)
    {


    }
}