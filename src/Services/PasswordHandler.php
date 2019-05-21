<?php

namespace App\Services;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class PasswordHandler
{
    /** @var EntityManagerInterface */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param $password
     *
     * @return array
     */
    public function generateHashAndSalt($password)
    {
        $salt = $this->getRandomString(128);
        $hash = hash('sha512', $password . $salt);

        return [
            User::COLUMN_PASSWORD => $hash,
            User::COLUMN_SALT => $salt,
        ];
    }

    public function generateAuthenticationLink()
    {
        return $this->getRandomString(128);
    }

    /**
     * @param User $user
     */
    public function generatePasswordLinkForUser(User $user)
    {
        $user->setChangePassowrdLink($this->generateAuthenticationLink());
        $user->setOptions($user->getOptions() | User::USER_CHANGING_PASSWORD);
        $this->entityManager->persist($user);

        $this->entityManager->flush();

    }

    /**
     * @param int $size
     *
     * @return string
     */
    private function getRandomString($size)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';

        for ($i = 0; $i < $size; $i++) {
            $index = rand(0, strlen($characters) - 1);
            $randomString .= $characters[$index];
        }

        return $randomString;
    }

}