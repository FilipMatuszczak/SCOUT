<?php

namespace App\Services;

use App\Entity\Post;
use App\Entity\Report;
use App\Repository\PostRepository;
use App\Security\UserProvider;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class ReportCreator
{
    /** @var EntityManager */
    private $entityManager;

    /** @var UserProvider */
    private $userProvider;

    /** @var Security */
    private $security;

    /** @var PostRepository */
    private $postRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        UserProvider $userProvider,
        Security $security,
        PostRepository $postRepository
    )
    {
        $this->entityManager = $entityManager;
        $this->userProvider = $userProvider;
        $this->security = $security;
        $this->postRepository = $postRepository;
    }

    public function createReportForPost($postId, $reason)
    {
        $newReport = new Report();
        $currentUsername = $this->security->getUser()->getUsername();
        $newReport
            ->setUser($this->userProvider->loadUserByUsername($currentUsername))
            ->setTimestamp(new \DateTime('now'))
            ->setOptions(Report::REPORT_NEW)
            ->setPost($this->postRepository->findOneBy(['postId' => $postId]))
            ->setReason($reason);

        $this->entityManager->persist($newReport);
        $this->entityManager->flush();
    }
}