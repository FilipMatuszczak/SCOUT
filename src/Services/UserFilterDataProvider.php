<?php


namespace App\Services;


use App\Repository\UserRepository;

class UserFilterDataProvider
{
    const USERS_PER_PAGE = 10;

    /** @var UserRepository */
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getUsersByFilters($page, $firstName, $lastName, $sorting)
    {
        $from = ($page-1) * self::USERS_PER_PAGE;
        $max = self::USERS_PER_PAGE;

        return $this->userRepository->fetchUsersByFilters(
            $from,
            $max,
            $this->convertToSqlSorting($sorting),
            $firstName,
            $lastName
        );
    }

    private function convertToSqlSorting($sorting)
    {
        switch ($sorting) {
            case 'A-Z':
                return ['dir' => 'ASC', 'sortKey' => 'firstname'];
            case 'Z-A':
                return ['dir' => 'DESC', 'sortKey' => 'firstname'];
            case 'Najstarsi':
                return ['dir' => 'ASC', 'sortKey' => 'dateOfBirth'];
            case 'Najmlodsi':
                return ['dir' => 'DESC', 'sortKey' => 'dateOfBirth'];
        }
    }
}