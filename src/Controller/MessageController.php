<?php

namespace App\Controller;

use App\Dto\MessageDisplay;
use App\Entity\Message;
use App\Security\UserProvider;
use App\Services\MessageCreator;
use App\Services\MessagesDataProvider;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;

class MessageController extends AbstractController
{
    /** @var MessageCreator */
    private $messageCreator;

    /** @var MessagesDataProvider */
    private $messagesDataProvider;

    /** @var Security */
    private $security;

    /** @var UserProvider */
    private $userProvider;

    public function __construct(MessageCreator $messageCreator, MessagesDataProvider $messagesDataProvider, Security $security, UserProvider $userProvider)
    {
        $this->messageCreator = $messageCreator;
        $this->messagesDataProvider = $messagesDataProvider;
        $this->security = $security;
        $this->userProvider = $userProvider;
    }

    public function allMessagesIndex()
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $userId = $this->userProvider->loadUserByUsername($this->security->getUser()->getUsername())->getUserId();

        $requests = $this->messagesDataProvider->getAllRequestsAddToProject($userId);
        $usernames = $this->messagesDataProvider->getAllConversationUsernames($userId);

        /** @var MessageDisplay[] $messageDisplays */
        $messageDisplays = $this->messagesDataProvider->getMessageDisplay($this->security->getUser()->getUsername(), $usernames);

        usort($messageDisplays, function (MessageDisplay $a, MessageDisplay $b) {
            return $a->getTimestamp()->getTimestamp() < $b->getTimestamp()->getTimestamp();
        });

        return $this->render('main/AllMessages.html.twig', ['requests' => $requests, 'messageDisplays' => $messageDisplays]);
    }

    public function writeMessageToUserAction(Request $request)
    {
        $messageText = $request->get('messageText');
        $receiverId = $request->get('receiverId');

        $this->messageCreator->createMessage($receiverId, $messageText);

        return $this->redirectToRoute('message_with_user', ['username' => $this->userProvider->loadUserById($receiverId)->getUsername()]);
    }

    public function MessagesWithUserIndex($username)
    {
        /** @var Message[] $messages */
        $messages = $this->messagesDataProvider->getAllMessagesBetweenUsers($this->security->getUser()->getUsername(), $username);

        return $this->render('main/Message.html.twig', ['messages' => $messages, 'receiverId' => $this->userProvider->loadUserByUsername($username)->getUserId()]);
    }
}