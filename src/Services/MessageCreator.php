<?php

namespace App\Services;

use App\Entity\Message;
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

    /**
     * @param EntityManagerInterface $entityManager
     * @param Security $security
     * @param UserProvider $userProvider
     */
    public function __construct(EntityManagerInterface $entityManager, Security $security, UserProvider $userProvider)
    {
        $this->entityManager = $entityManager;
        $this->security = $security;
        $this->userProvider = $userProvider;
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

}