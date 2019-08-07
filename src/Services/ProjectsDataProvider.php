<?php


namespace App\Services;


use App\Entity\Project;
use App\Repository\ProjectRepository;

class ProjectsDataProvider
{
    /** @var ProjectRepository */
    private $projectRepository;

    public function __construct(ProjectRepository $projectRepository)
    {
        $this->projectRepository = $projectRepository;
    }

    /**
     * @param $sorting
     * @param $dir
     * @param $page
     * @param null $title
     * @param null $author
     * @param null $technology
     *
     * @return Project[]
     */
    public function getProjectsByFilters($sorting, $dir, $page, $title = null, $author = null, $technology = null)
    {
        return $this->projectRepository->fetchProjectsByFilters($sorting, $dir, $page, $page+10);
    }
}