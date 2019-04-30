<?php

namespace App\Controller;

use App\Services\MailerService;
use App\Services\RegisterHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

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
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function createUserAction(Request $request)
    {
        $newUserData = json_decode(
            $request->getContent(),
            true
        );

        $user = $this->registerHandler->registerUser($newUserData);
        $this->mailerService->sendAuthenticationEmail($user->getEmail());

        return new JsonResponse(
            [
                'status' => 'ok',
            ],
            JsonResponse::HTTP_CREATED
        );
    }
}