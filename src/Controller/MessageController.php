<?php

namespace App\Controller;

use App\Dto\MessageDisplay;
use App\Entity\AddUserToProjectRequest;
use App\Entity\Message;
use App\Repository\AddUserToProjectRequestRepository;
use App\Security\UserProvider;
use App\Services\MessageCreator;
use App\Services\MessagesDataProvider;
use App\Services\ProjectsDataProvider;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
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

    /** @var ProjectsDataProvider */
    private $projectDataProvider;

    /** @var EntityManager */
    private $entityManager;

    /** @var AddUserToProjectRequestRepository */
    private $addUserToProjectRequestRepository;

    public function __construct(
        MessageCreator $messageCreator,
        MessagesDataProvider $messagesDataProvider,
        Security $security,
        UserProvider $userProvider,
        ProjectsDataProvider $projectsDataProvider,
        EntityManagerInterface $entityManager,
        AddUserToProjectRequestRepository $addUserToProjectRequestRepository
    )
    {
        $this->messageCreator = $messageCreator;
        $this->messagesDataProvider = $messagesDataProvider;
        $this->security = $security;
        $this->userProvider = $userProvider;
        $this->projectDataProvider = $projectsDataProvider;
        $this->entityManager = $entityManager;
        $this->addUserToProjectRequestRepository = $addUserToProjectRequestRepository;
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

    public function decideAddToProjectAction(Request $request)
    {
        $decision = $request->get('decision');
        $userId = $request->get('userId');
        $projectId = $request->get('projectId');
        $addUserToProjectRequestId = $request->get('addUserToProjectRequestId');
        $user = $this->userProvider->loadUserById($userId);

        $request = $this->addUserToProjectRequestRepository->findOneBy(['requestId' => $addUserToProjectRequestId]);

        if ($decision === 'accept') {
            $project = $this->projectDataProvider->getProjectById($projectId);
            $project->addUser($user);
            $request->setOptions(AddUserToProjectRequest::OPTIONS_ACCEPTED);
            $this->entityManager->persist($project);
        } else {
            $request->setOptions(AddUserToProjectRequest::OPTIONS_ACCEPTED);
        }
        $this->entityManager->persist($request);
        $this->entityManager->flush();

        return $this->redirectToRoute('all_messages');
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