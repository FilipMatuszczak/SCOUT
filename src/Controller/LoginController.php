<?php

namespace App\Controller;

use App\Entity\User;
use App\Security\LoginFormAuthenticator;
use App\Security\UserProvider;
use App\Services\MailerService;
use App\Services\PasswordHandler;
use App\Services\RegisterHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
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

    /** @var GuardAuthenticatorHandler */
    private $guardAuthenticatorHandler;

    /** @var PasswordHandler */
    private $passwordHandler;

    /**
     * @param RegisterHandler $registerHandler
     * @param MailerService $mailerService
     * @param AuthenticationUtils $authenticationUtils
     * @param LoginFormAuthenticator $loginFormAuthenticator
     * @param UserProvider $userProvider
     * @param GuardAuthenticatorHandler $guardAuthenticatorHandler
     * @param PasswordHandler $passwordHandler
     */
    public function __construct(
        RegisterHandler $registerHandler,
        MailerService $mailerService,
        AuthenticationUtils $authenticationUtils,
        LoginFormAuthenticator $loginFormAuthenticator,
        UserProvider $userProvider,
        GuardAuthenticatorHandler $guardAuthenticatorHandler,
        PasswordHandler $passwordHandler
    )
    {
        $this->registerHandler = $registerHandler;
        $this->mailerService = $mailerService;
        $this->authenticationUtils = $authenticationUtils;
        $this->loginFormAuthenticator = $loginFormAuthenticator;
        $this->userProvider = $userProvider;
        $this->guardAuthenticatorHandler = $guardAuthenticatorHandler;
        $this->passwordHandler = $passwordHandler;
    }

    /**
     * @param string $message
     *
     * @return Response
     */
    public function indexAction($message = null)
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('main');
        }

        $error = $this->authenticationUtils->getLastAuthenticationError();
        $lastUsername = $this->authenticationUtils->getLastUsername();

        return $this->render('main/index.html.twig', ['last_username' => $lastUsername, 'error' => $error, 'message' => $message]);
    }

    /**
     * @param Request $request
     * @param string $username
     *
     * @return JsonResponse
     */
    public function userExistsAction(Request $request, $username)
    {
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
     * @param $username
     * @param $changePasswordLink
     *
     * @return Response
     */
    public function changePasswordAction($username, $changePasswordLink)
    {
        $user = $this->mailerService->verifyChangePasswordLink($username, $changePasswordLink);

        if (!$user) {
            throw $this->createNotFoundException(
                'Cannot verify user ' . $username
            );
        }

        return $this->render('main/changepswd-web.html.twig', ['name' => $username]);
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
            User::COLUMN_AUTHENTICATION_LINK => $user->getAuthenticationLink(),
        ]);
    }

    /**
     * @return Response
     */
    public function emailConfirmationSentAction()
    {
        return $this->render('main/registered-web.html.twig', []);
    }

    /**
     * @param Request $request
     *
     * @return Response|null
     */
    public function loginEmailAction(Request $request)
    {
        $user = $this->userProvider->loadUserByUsername($request->get('username'));

        return $this->guardAuthenticatorHandler
            ->authenticateUserAndHandleSuccess(
                $user,
                $request,
                $this->loginFormAuthenticator,
                'main'
            );
    }

    /**
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function submitChangePasswordAction(Request $request)
    {
        $user = $this->userProvider->loadUserByUsername($request->get('username'));
        $newPassowrd = $request->get('password');

        $credentials = $this->passwordHandler->generateHashAndSalt($newPassowrd);
        $this->passwordHandler->updateUserCredentials($credentials, $user);
        $this->get('session')->getFlashBag()->set('notice', 'Twoje hasło zostało zmienione, możesz teraz się zalogować używając swojego nowego hasła');

        return $this->redirectToRoute('index', []);
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function changePasswordEmailAction(Request $request)
    {
        $user = $this->userProvider->loadUserByUsername($request->get('username'));

        if (empty($user)) {
            throw $this->createNotFoundException(
                'Cannot verify user ' . $request->get('username')
            );
        }

        $this->passwordHandler->generatePasswordLinkForUser($user);

        return $this->forward('App\Controller\MailController::sendChangePasswordAction', [
            User::COLUMN_EMAIL => $user->getEmail(),
            User::COLUMN_USERNAME => $user->getUsername(),
            User::COLUMN_CHANGE_PASSWORD_LINK => $user->getChangePassowrdLink(),
        ]);
    }
}