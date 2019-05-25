<?php


namespace App\Services;

use App\Entity\Post;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class PostCreator
{
    /** @var EntityManagerInterface */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function createPostForUser(User $user, $content)
    {
        $newPost = new Post();
        $newPost->setText($content)->setProject(null)->setTimestamp(new \DateTime('now'))->setUser($user);

        $this->entityManager->persist($newPost);
        $this->entityManager->flush();
    }
}