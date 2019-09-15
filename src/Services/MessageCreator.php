<?php

namespace App\Services;

use App\Entity\AddUserToProjectRequest;
use App\Entity\Message;
use App\Repository\ProjectRepository;
use App\Security\UserProvider;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class MessageCreator
{
    /** @var EntityManager */
    private $entityManager;

    /** @var Security */
    private $security;

    /** @var UserProvider */
    private $userProvider;

    /** @var ProjectRepository */
    private $projectRepository;

    /**
     * @param EntityManagerInterface $entityManager
     * @param Security $security
     * @param UserProvider $userProvider
     * @param ProjectRepository $projectRepository
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        Security $security,
        UserProvider $userProvider,
        ProjectRepository $projectRepository
    )
    {
        $this->entityManager = $entityManager;
        $this->security = $security;
        $this->userProvider = $userProvider;
        $this->projectRepository = $projectRepository;
    }

    public function createMessage($receiverId, $messageText)
    {
        $message = new Message();

        $message
            ->setTimestamp(new \DateTime('now'))
            ->setText($messageText)
            ->setReceiver($this->userProvider->loadUserById($receiverId))
            ->setSender($this->userProvider->loadUserByUsername($this->security->getUser()->getUsername()));

        $this->entityManager->persist($message);
        $this->entityManager->flush();
    }

    public function createAddUserToProjectRequest($projectId, $messageText)
    {
        $addUserToProject = new AddUserToProjectRequest();

        $addUserToProject
            ->setTimestamp(new \DateTime('now'))
            ->setText($messageText)
            ->setOptions(AddUserToProjectRequest::OPTIONS_NEW)
            ->setUser($this->userProvider->loadUserByUsername($this->security->getUser()->getUsername()))
            ->setProject($this->projectRepository->findOneBy(['projectId' => $projectId]));

        $this->entityManager->persist($addUserToProject);
        $this->entityManager->flush();
    }
}