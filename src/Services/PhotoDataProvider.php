<?php

namespace App\Services;


use App\Entity\User;
use App\Repository\PostRepository;
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

    /** @var PostRepository */
    private $postRepository;

    public function __construct(
        Security $security,
        UserRepository $userRepository,
        ProjectRepository $projectRepository,
        PostRepository $postRepository
    )
    {
        $this->security = $security;
        $this->userRepository = $userRepository;
        $this->projectRepository = $projectRepository;
        $this->postRepository = $postRepository;
    }

    public function getCurrentUserPhotoData()
    {
        $username = $this->security->getUser()->getUsername();
        $photo = $this->userRepository->findOneBy([User::COLUMN_USERNAME => $username])->getPhoto();

        if ($userPhoto = $this->getEncodedPhoto($photo)) {
            return $userPhoto;
        }

        return $this->getEncodedPhoto(fopen(getcwd() . '\web\images\default_user.jpg', "r"));
    }

    public function getPhotoByUsername($username)
    {
        if ($username === 'SCOUT NEWSLETTER')
        {
            $username = 'admin';
        }
        $photo = $this->userRepository->findOneBy([User::COLUMN_USERNAME => $username])->getPhoto();

        if ($userPhoto = $this->getEncodedPhoto($photo)) {
            return $userPhoto;
        }

        return $this->getEncodedPhoto(fopen(getcwd() . '\web\images\default_user.jpg', "r"));
    }

    public function getPhotoByProjectTitle($title)
    {
        $photo = $this->projectRepository->findOneBy(['title' => $title])->getPhoto();

        if ($projectPhoto = $this->getEncodedPhoto($photo)) {
            return $projectPhoto;
        }

        return $this->getEncodedPhoto(fopen(getcwd() . '\web\images\default_project.jpg', "r"));
    }

    public function getPhotoByPostId($postId)
    {
        $photo = $this->postRepository->findOneBy(['postId' => $postId])->getPhoto();

        if ($projectPhoto = $this->getEncodedPhoto($photo)) {
            return $projectPhoto;
        }

        return null;
    }

    private function getEncodedPhoto($photo)
    {

        if ($photo) {
            rewind($photo);
            $photo = stream_get_contents($photo);
            return base64_encode($photo);
        }

        return null;
    }
}