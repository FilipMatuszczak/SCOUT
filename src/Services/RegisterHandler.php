<?php

namespace App\Services;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMException;

class RegisterHandler
{
    /** @var UserRepository */
    private $userRepository;

    /** @var PasswordHandler */
    private $passwordHandler;

    /** @var EntityManagerInterface */
    private $entityManager;

    /**
     * @param UserRepository $userRepository
     * @param PasswordHandler $passwordHandler
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        UserRepository $userRepository,
        PasswordHandler $passwordHandler,
        EntityManagerInterface $entityManager)
    {
        $this->userRepository = $userRepository;
        $this->passwordHandler = $passwordHandler;
        $this->entityManager = $entityManager;
    }

    /**
     * @param array $userData
     */
    public function registerUser(array $userData)
    {
        $username = $userData[User::COLUMN_USERNAME];
        $firstname = $userData[User::COLUMN_FIRST_NAME];
        $lastname = $userData[User::COLUMN_LAST_NAME];
        $email = $userData[User::COLUMN_EMAIL];
        $password = $userData[User::COLUMN_PASSWORD];

        $hashAndSalt = $this->passwordHandler->generateHashAndSalt($password);

        $user = new User();
        $user
            ->setUsername($username)
            ->setFirstname($firstname)
            ->setLastname($lastname)
            ->setEmail($email)
            ->setPassword($hashAndSalt[User::COLUMN_PASSWORD])
            ->setSalt($hashAndSalt[User::COLUMN_SALT])
            ->setOptions(User::USER_CREATED)
            ->setAuthenticationLink($this->passwordHandler->generateAuthenticationLink());

        $this->entityManager->persist($user);

        $this->entityManager->flush();

        return $user;
    }
}