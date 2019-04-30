<?php


namespace App\Services;

use Swift_Mailer;

class MailerService
{
    /** @var Swift_Mailer */
    private $mailer;

    public function __construct(Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendAuthenticationEmail($email)
    {
        $message = (new \Swift_Message('Hello Email'))
            ->setFrom('scoutregister1@gmail.com')
            ->setTo($email)
            ->setBody(
                'dziala'
            );

        $this->mailer->send($message);
    }
}