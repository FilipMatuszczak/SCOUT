<?php

namespace App\Services;

use App\Repository\PostRepository;
use App\Repository\ProjectRepository;
use App\Repository\TechnologyRepository;
use App\Repository\UserProjectRepository;
use App\Repository\UserRepository;

class ProjectsDataProvider
{
    const PROJECTS_PER_PAGE = 10;
    const USER_NOT_MEMBER = 0;
    const USER_MEMBER = 1;
    const USER_AUTHOR = 2;

    /** @var ProjectRepository */
    private $projectRepository;

    /** @var TechnologyRepository */
    private $technologyRepository;

    /** @var UserRepository */
    private $userRepository;

    /** @var UserProjectRepository */
    private $userProjectRepository;

    /** @var PostRepository */
    private $postRepository;

    public function __construct(
        ProjectRepository $projectRepository,
        TechnologyRepository $technologyRepository,
        UserRepository $userRepository,
        UserProjectRepository $userProjectRepository,
        PostRepository $postRepository
    )
    {
        $this->projectRepository = $projectRepository;
        $this->technologyRepository = $technologyRepository;
        $this->userRepository = $userRepository;
        $this->userProjectRepository = $userProjectRepository;
        $this->postRepository = $postRepository;
    }

    public function getProjectsByFilters($sorting, $page, $technologyName, $title, $memberName)
    {
        $from = ($page - 1) * self::PROJECTS_PER_PAGE;
        $max = self::PROJECTS_PER_PAGE;

        if (!empty($technologyName)) {
            $technology = $this->technologyRepository->findOneBy(['name' => $technologyName]);
            if (empty($technology)) {
                return [];
            }
        } else {
            $technology = null;
        }

        if (!empty($memberName)) {
            $member = $this->userRepository->findOneBy(['username' => $memberName]);
            if (empty($member)) {
                return [];
            }
        } else {
            $member = null;
        }

        return $this->projectRepository->fetchProjectsByFilters($from, $max, $this->convertToSqlSorting($sorting), $title, $technology, $member);
    }

    public function getProjectById($projectId)
    {
        return $this->projectRepository->findOneBy(['projectId' => $projectId]);
    }

    public function getUserProjectStatus($userId, $projectId)
    {
        $status = $this->userProjectRepository->getOptionsByUserIdAndProjectId($userId, $projectId);
        if ($status === false) {
            return self::USER_NOT_MEMBER;
        }

        switch ($status) {
            case $status | 1:
                return self::USER_AUTHOR;
            default:
                return self::USER_MEMBER;
        }
    }

    public function getProjectPosts($project)
    {
        return $this->postRepository->fetchProjectPosts($project);
    }

    private function convertToSqlSorting($sorting)
    {
        switch ($sorting) {
            case 'A-Z':
                return ['dir' => 'ASC', 'sortKey' => 'title'];
            case 'Z-A':
                return ['dir' => 'DESC', 'sortKey' => 'title'];
            case 'Najnowsze':
                return ['dir' => 'DESC', 'sortKey' => 'createdDate'];
            case 'Najstarsze':
                return ['dir' => 'ASC', 'sortKey' => 'createdDate'];
        }
    }
}