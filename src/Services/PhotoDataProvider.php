<?php

namespace App\Services;


use App\Entity\Project;
use App\Entity\User;
use App\Repository\ProjectRepository;
use App\Repository\UserRepository;
use Symfony\Component\Security\Core\Security;

class PhotoDataProvider
{
    /** @var Security */
    private $security;

    /** @var UserRepository */
    private $userRepository;

    /** @var ProjectRepository */
    private $projectRepository;

    public function __construct(Security $security, UserRepository $userRepository, ProjectRepository $projectRepository)
    {
        $this->security = $security;
        $this->userRepository = $userRepository;
        $this->projectRepository = $projectRepository;
    }

    public function getCurrentUserPhotoData()
    {
        $username = $this->security->getUser()->getUsername();
        $photo = $this->userRepository->findOneBy([User::COLUMN_USERNAME => $username])->getPhoto();

        return $this->getEncodedPhoto($photo);
    }

    public function getPhotoByUsername($username)
    {
        $photo = $this->userRepository->findOneBy([User::COLUMN_USERNAME => $username])->getPhoto();

        return $this->getEncodedPhoto($photo);
    }

    public function getPhotoByProjectTitle($title)
    {
        $photo = $this->projectRepository->findOneBy(['title' => $title])->getPhoto();

        return $this->getEncodedPhoto($photo);
    }

    private function getEncodedPhoto($photo)
    {

        if ($photo){
            rewind($photo);
            $photo = stream_get_contents($photo);
            return base64_encode($photo);
        }

        return null;
    }
}