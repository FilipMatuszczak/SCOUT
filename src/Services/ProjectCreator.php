<?php

namespace App\Services;

use App\Entity\Project;
use App\Entity\User;
use App\Repository\TechnologyRepository;
use App\Repository\UserProjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\File;

class ProjectCreator
{
    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var UserProjectRepository */
    private $userProjectRepository;

    /** @var TechnologyRepository */
    private $technologyRepository;

    public function __construct
    (
        EntityManagerInterface $entityManager,
        UserProjectRepository $userProjectRepository,
        TechnologyRepository $technologyRepository
    )
    {
        $this->entityManager = $entityManager;
        $this->userProjectRepository = $userProjectRepository;
        $this->technologyRepository = $technologyRepository;
    }

    public function createProject($title, $description, File $photo, array $technologyNames, User $author)
    {
        $project = new Project();

        $project->setTitle($title);
        $project->setDescription($description);

        $strm = fopen($photo->getRealPath(),'rb');
        $project->setPhoto(stream_get_contents($strm));

        $project->setCreatedDate(new \DateTime('now'));

        $project->addUser($author);
        foreach ($technologyNames as $technologyName) {
            $project->addTechnology($this->technologyRepository->findOneBy(['name' => $technologyName]));
        }
        $this->entityManager->persist($project);
        $this->entityManager->flush();

        $this->userProjectRepository->setUserAsAuthor($author->getUserId(), $project->getProjectId());
    }
}