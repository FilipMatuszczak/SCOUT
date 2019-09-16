<?php


namespace App\Services;


use App\Entity\Post;
use App\Entity\Project;
use App\Entity\User;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\File;

class PostCreator
{
    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var PostRepository */
    private $postRepository;

    public function __construct(EntityManagerInterface $entityManager, PostRepository $postRepository)
    {
        $this->entityManager = $entityManager;
        $this->postRepository = $postRepository;
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
        if ($photo !== null) {
            $strm = fopen($photo->getRealPath(), 'rb');
            $newPost->setPhoto(stream_get_contents($strm));
        }

        $this->entityManager->persist($newPost);
        $this->entityManager->flush();
    }

    public function createPostForProject(User $user, $content, Project $project, File $photo = null)
    {
        $newPost = new Post();
        $newPost->setText($content)->setProject(null)->setTimestamp(new \DateTime('now'))->setUser($user)->setProject($project);
        if ($photo !== null) {
            $strm = fopen($photo->getRealPath(), 'rb');
            $newPost->setPhoto(stream_get_contents($strm));
        }

        $this->entityManager->persist($newPost);
        $this->entityManager->flush();
    }

    public function deletePost($postId)
    {
        $post = $this->postRepository->findOneBy(['postId' => $postId]);

        $this->entityManager->remove($post);
        $this->entityManager->flush();
    }
}