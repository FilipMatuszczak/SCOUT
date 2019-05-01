<?php

namespace App\Controller;

use Swift_Mailer;
use Swift_Message;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class MailController extends AbstractController
{
    /** @var Swift_Mailer */
    private $mailer;

    public function __construct(Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * @param string $email
     * @param string $username
     * @param string $authenticationLink
     *
     * @return JsonResponse
     */
    public function sendRegisterMailAction($email, $username, $authenticationLink)
    {
        $verificationUrl = $this->generateUrl
        (
            'authenticate_account',
            [
                'username' => $username,
                'authenticationLink' => $authenticationLink,
                ],
            UrlGeneratorInterface::ABSOLUTE_URL
        );

        $message = (new Swift_Message('Scout - Confirmation email'))
            ->setFrom('scoutregister1@gmail.com')
            ->setTo($email)
            ->setBody(
                $this->renderView(
                    'main/registration.html.twig',
                    [
                        'name' => $username,
                        'verificationUrl' => $verificationUrl,
                    ]
                ),
                'text/html'
            );

        $this->mailer->send($message);

        return new JsonResponse(
            [
                'status' => 'ok',
            ], JsonResponse::HTTP_CREATED);
    }
}