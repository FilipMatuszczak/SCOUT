<?php

namespace App\Services;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;

class MailerService
{
    /** @var UserRepository */
    private $userRepository;

    /** @var EntityManagerInterface */
    private $entityManager;

    public function __construct(UserRepository $userRepository, EntityManagerInterface $entityManager)
    {
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * @param string $username
     * @param string $authenticationLink
     *
     * @return User|null
     */
    public function verifyAuthenticationLink($username, $authenticationLink)
    {
        $user = $this->userRepository
            ->findCreatedUserByUsernameAndAuthenticationLink($username, $authenticationLink);

        if ($user)
        {
            $user->setOptions($user->getOptions() | User::USER_VERIFIED);

            $this->entityManager->merge($user);
            $this->entityManager->flush();

            return $user;
        }

        return null;
    }
}