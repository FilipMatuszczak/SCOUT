<?php

namespace App\Controller;

use Swift_Mailer;
use Swift_Message;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
     * @return \Symfony\Component\HttpFoundation\Response
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

        return $this->redirectToRoute('email_confirmation_sent', [], 301);
    }

    public function sendChangePasswordAction($email, $username, $changePasswordLink)
    {
        $changePasswordLink = $this->generateUrl
        (
            'change_password',
            [
                'username' => $username,
                'changePasswordLink' => $changePasswordLink,
            ],
            UrlGeneratorInterface::ABSOLUTE_URL
        );

        $message = (new Swift_Message('Scout - Change password'))
            ->setFrom('scoutregister1@gmail.com')
            ->setTo($email)
            ->setBody(
                $this->renderView(
                    'main/change_password_email.html.twig',
                    [
                        'name' => $username,
                        'changePasswordUrl' => $changePasswordLink,
                    ]
                ),
                'text/html'
            );

        $this->mailer->send($message);

        return $this->redirectToRoute('index', [], 301);
    }
}