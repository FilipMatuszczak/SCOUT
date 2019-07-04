<?php

namespace App\Services;

use App\Entity\User;
use App\Repository\UserRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;

/** */
class ProfileEditHandler
{
    /** @var UserRepository $userRepository */
    private $userRepository;

    /** @var EntityManagerInterface $entityManager */
    private $entityManager;

    /**
     * @param UserRepository $userRepository
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        UserRepository $userRepository,
        EntityManagerInterface $entityManager)
    {
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * @param User   $user
     * @param string $filename
     * @param string $tempFilePath
     */
    public function saveNewProfilePhoto(User $user, $filename, $tempFilePath)
    {
        $content = file_get_contents($_FILES[$filename][$tempFilePath]);
        $user->setPhoto($content);

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    /**
     * @param User     $user
     * @param string   $firstname
     * @param string   $lastname
     * @param Datetime $birthDate
     * @param string   $info
     */
    public function saveBasicInfo(User $user, $firstname, $lastname, $birthDate, $info)
    {
        $user->setFirstname($firstname);
        $user->setLastname($lastname);
        $user->setInfo($info);
        $user->setDateOfBirth($birthDate);

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}