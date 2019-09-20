<?php

namespace App\Services;

use App\Dto\MessageDisplay;
use App\Entity\Message;
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
        $projectData = $this->userProjectRepository->getProjectsByAuthorId($userId);
        $projectIds = [];

        for ($i = 0; $i<count($projectData); $i++)
        {
            $projectIds[] = $projectData[$i]['project_id'];

        }

        $requests = $this->addUserToProjectRequestRepository->findBy(['project' => $this->projectRepository->findBy(['projectId' => ($projectIds)])]);

        return $requests;
    }

    public function getAllConversationUsernames($userId)
    {
        $user = $this->userRepository->findOneBy(['userId' => $userId]);

        $receiverMessages = $this->messageRepository->findBy(['receiver' => $user]);
        $senderMessages = $this->messageRepository->findBy(['sender' => $user]);

        $usernames = [];

        foreach ($receiverMessages as $message) {
            if (!in_array($message->getSender()->getUsername(), $usernames)) {
                $usernames[] = $message->getSender()->getUsername();
            }
        }

        foreach ($senderMessages as $message) {
            if (!in_array($message->getReceiver()->getUsername(), $usernames)) {
                $usernames[] = $message->getReceiver()->getUsername();
            }
        }

        return $usernames;
    }

    public function getMessageDisplay($currentUsername, array $usernames)
    {
        $users = $this->userRepository->findBy(['username' => $usernames]);

        $currentUser = $this->userRepository->findOneBy(['username' => $currentUsername]);

        $messageDisplays = [];

        foreach ($users as $user) {
            $latestReceiverMessage = $this->messageRepository->findOneBy(['sender' => $user, 'receiver' => $currentUser], ['timestamp' => 'DESC']);
            $latestSenderMessage = $this->messageRepository->findOneBy(['receiver' => $user, 'sender' => $currentUser], ['timestamp' => 'DESC']);

            $messagedisplay = new MessageDisplay();

            if ($latestReceiverMessage && $latestSenderMessage) {
                if ($latestReceiverMessage->getTimestamp() > $latestSenderMessage->getTimestamp()) {
                    $messagedisplay->setTimestamp(new \DateTime($latestReceiverMessage->getTimestamp()->format('Y-m-d H:i:s')));
                    $messagedisplay->setUsername($user->getUsername());
                    $messagedisplay->setMessage($latestReceiverMessage->getText());
                    $messagedisplay->setIsCurrentUserSender(false);

                } else {
                    $messagedisplay->setTimestamp(new \DateTime($latestSenderMessage->getTimestamp()->format('Y-m-d H:i:s')));
                    $messagedisplay->setUsername($user->getUsername());
                    $messagedisplay->setMessage($latestSenderMessage->getText());
                    $messagedisplay->setIsCurrentUserSender(true);
                }
            } else {
                if ($latestSenderMessage === null) {
                    $messagedisplay->setTimestamp(new \DateTime($latestReceiverMessage->getTimestamp()->format('Y-m-d H:i:s')));
                    $messagedisplay->setUsername(($user->getUsername()));
                    $messagedisplay->setMessage($latestReceiverMessage->getText());
                    $messagedisplay->setIsCurrentUserSender(false);

                } else {
                    $messagedisplay->setTimestamp(new \DateTime($latestSenderMessage->getTimestamp()->format('Y-m-d H:i:s')));
                    $messagedisplay->setUsername($user->getUsername());
                    $messagedisplay->setMessage($latestSenderMessage->getText());
                    $messagedisplay->setIsCurrentUserSender(true);

                }
            }

            $messageDisplays[] = $messagedisplay;
        }

        return $messageDisplays;
    }

    public function getAllMessagesBetweenUsers($ownerName, $username)
    {
        $owner = $this->userRepository->findOneBy(['username' => $ownerName]);
        $user = $this->userRepository->findOneBy(['username' => $username]);

        $halfMessages = $this->messageRepository->findBy(['receiver' => $owner, 'sender' => $user]);
        $otherHalfMessages = $this->messageRepository->findBy(['receiver' => $user, 'sender' => $owner]);

        $messages = array_merge($halfMessages, $otherHalfMessages);

        usort($messages, function (Message $a, Message $b) {
            return $a->getTimestamp()->getTimestamp() > $b->getTimestamp()->getTimestamp();
        });

        return $messages;
    }
}