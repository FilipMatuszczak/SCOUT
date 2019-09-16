<?php


namespace App\Services;


use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

class UserHandler
{
    /** @var UserRepository */
    private $userRepository;

    /** @var EntityManager */
    private $entityManager;

    public function __construct(UserRepository $userRepository, EntityManagerInterface $entityManager)
    {
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;
    }

    public function banUser($userId)
    {
        $user = $this->userRepository->findOneBy(['userId' => $userId]);

        $user->setOptions($user->getOptions() | User::USER_BANNED);
    }
}