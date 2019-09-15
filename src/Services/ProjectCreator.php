<?php

namespace App\Services;

use App\Entity\Project;
use App\Entity\User;
use App\Repository\ProjectRepository;
use App\Repository\TechnologyRepository;
use App\Repository\UserProjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class ProjectCreator
{
    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var UserProjectRepository */
    private $userProjectRepository;

    /** @var TechnologyRepository */
    private $technologyRepository;

    /** @var ProjectRepository */
    private $projectRepository;

    /** @var ProjectsDataProvider */
    private $projectsDataProvider;

    public function __construct
    (
        EntityManagerInterface $entityManager,
        UserProjectRepository $userProjectRepository,
        TechnologyRepository $technologyRepository,
        ProjectRepository $projectRepository,
        ProjectsDataProvider $projectsDataProvider
    )
    {
        $this->entityManager = $entityManager;
        $this->userProjectRepository = $userProjectRepository;
        $this->technologyRepository = $technologyRepository;
        $this->projectRepository = $projectRepository;
        $this->projectsDataProvider = $projectsDataProvider;
    }

    public function createProject($title, $description, File $photo, array $technologyNames, User $author)
    {
        $project = new Project();

        $project->setTitle($title);
        $project->setDescription($description);

        $strm = fopen($photo->getRealPath(), 'rb');
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

    public function updateProject($projectId, $title, $description, File $photo, array $technologyNames, User $editor)
    {
        $project = $this->projectRepository->findOneBy(['projectId' => $projectId]);
        if ($this->projectsDataProvider->getUserProjectStatus($editor->getUserId(), $projectId) != ProjectsDataProvider::USER_AUTHOR){
            throw new AccessDeniedHttpException();
        }
        $project->setTitle($title);
        $project->setDescription($description);

        $strm = fopen($photo->getRealPath(), 'rb');
        $project->setPhoto(stream_get_contents($strm));

        $this->entityManager->persist($project);
        $this->entityManager->flush();
    }
}