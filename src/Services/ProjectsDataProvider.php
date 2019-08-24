<?php

namespace App\Services;

use App\Repository\ProjectRepository;
use App\Repository\TechnologyRepository;
use App\Repository\UserRepository;

class ProjectsDataProvider
{
    const PROJECTS_PER_PAGE = 10;

    /** @var ProjectRepository */
    private $projectRepository;

    /** @var TechnologyRepository */
    private $technologyRepository;

    /** @var UserRepository */
    private $userRepository;

    public function __construct(
        ProjectRepository $projectRepository,
        TechnologyRepository $technologyRepository,
        UserRepository $userRepository
    )
    {
        $this->projectRepository = $projectRepository;
        $this->technologyRepository = $technologyRepository;
        $this->userRepository = $userRepository;
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