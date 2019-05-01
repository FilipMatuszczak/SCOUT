<?php

namespace App\Controller;

use Swift_Mailer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class MailController extends AbstractController
{
    /** @var Swift_Mailer */
    private $mailer;

    public function __construct(Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendRegisterMailAction($email, $firstname)
    {
        $message = (new \Swift_Message('Hello Email'))
            ->setFrom('scoutregister1@gmail.com')
            ->setTo($email)
            ->setBody(
                $this->renderView(
                    'main/registration.html.twig',
                    ['name' => $firstname]
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