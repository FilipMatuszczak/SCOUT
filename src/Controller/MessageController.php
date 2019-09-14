<?php

namespace App\Controller;

use App\Services\MessageCreator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class MessageController extends AbstractController
{
    /** @var MessageCreator */
    private $messageCreator;

    /**
     * @param MessageCreator $messageCreator
     */
    public function __construct(MessageCreator $messageCreator)
    {
        $this->messageCreator = $messageCreator;
    }

    public function writeMessageToUserAction(Request $request)
    {
       $messageText = $request->get('messageText');
       $receiverId = $request->get('receiverId');

       $this->messageCreator->createMessage($receiverId, $messageText);

       return $this->redirectToRoute('main');
    }
}