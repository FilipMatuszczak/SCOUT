<?php

namespace App\Services;

use App\Repository\AddUserToProjectRequestRepository;
use App\Repository\MessageRepository;
use App\Repository\ProjectRepository;
use App\Repository\UserProjectRepository;
use App\Repository\UserRepository;

class MessagesDataProvider
{
    /** @var UserProjectRepository */
    private $userProjectRepository;

    /** @var AddUserToProjectRequestRepository */
    private $addUserToProjectRequestRepository;

    /** @var ProjectRepository */
    private $projectRepository;

    /** @var MessageRepository */
    private $messageRepository;

    /** @var UserRepository */
    private $userRepository;

    public function __construct(
        UserProjectRepository $userProjectRepository,
        AddUserToProjectRequestRepository $addUserToProjectRequestRepository,
        ProjectRepository $projectRepository,
        MessageRepository $messageRepository,
        UserRepository $userRepository
    )
    {
        $this->userProjectRepository = $userProjectRepository;
        $this->addUserToProjectRequestRepository = $addUserToProjectRequestRepository;
        $this->projectRepository = $projectRepository;
        $this->messageRepository = $messageRepository;
        $this->userRepository = $userRepository;
    }

    public function getAllRequestsAddToProject($userId)
    {
        $projectIds = $this->userProjectRepository->getProjectsByAuthorId($userId);

        $requests = $this->addUserToProjectRequestRepository->findBy(['project' => $this->projectRepository->findBy(['projectId' => $projectIds])]);

        return $requests;
    }

    public function getAllConversationUsernames($userId)
    {
        $user = $this->userRepository->findOneBy(['userId' => $userId]);

        $receiverMessages = $this->messageRepository->findBy(['receiver' =>$user]);
        $senderMessages = $this->messageRepository->findBy(['sender' => $user]);

        $usernames = [];

        foreach ($receiverMessages as $message)
        {
            if (!in_array($message->getSender()->getUsername(), $usernames))
            {
                $usernames[] = $message->getSender()->getUsername();
            }
        }

        foreach ($senderMessages as $message)
        {
            if (!in_array($message->getReceiver()->getUsername(), $usernames))
            {
                $usernames[] = $message->getReceiver()->getUsername();
            }
        }

        return $usernames;
    }
}