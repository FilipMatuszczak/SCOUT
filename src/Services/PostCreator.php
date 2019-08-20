<?php


namespace App\Services;


use App\Entity\Post;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\File;

class PostCreator
{
    /** @var EntityManagerInterface */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param User $user
     * @param $content
     * @param File|null $photo
     */
    public function createPostForUser(User $user, $content, File $photo = null)
    {
        $newPost = new Post();
        $newPost->setText($content)->setProject(null)->setTimestamp(new \DateTime('now'))->setUser($user);
        if ($photo !== null){
        $strm = fopen($photo->getRealPath(),'rb');
        $newPost->setPhoto(stream_get_contents($strm));}

        $this->entityManager->persist($newPost);
        $this->entityManager->flush();
    }
}