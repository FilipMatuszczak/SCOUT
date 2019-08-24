<?php

namespace App\Services;

use App\Repository\CityRepository;
use App\Repository\LanguageRepository;
use App\Repository\TechnologyRepository;
use App\Repository\UserRepository;

class UserFilterDataProvider
{
    const USERS_PER_PAGE = 10;

    /** @var UserRepository */
    private $userRepository;

    /** @var TechnologyRepository */
    private $technologyRepository;

    /** @var TechnologyRepository */
    private $languageRepository;

    /** @var CityRepository */
    private $cityRepository;

    public function __construct(
        UserRepository $userRepository,
        TechnologyRepository $technologyRepository,
        LanguageRepository $languageRepository,
        CityRepository $countryRepository
    )
    {
        $this->userRepository = $userRepository;
        $this->technologyRepository = $technologyRepository;
        $this->languageRepository = $languageRepository;
        $this->cityRepository = $countryRepository;
    }

    public function getUsersByFilters($page, $firstName, $lastName, $sorting, $languageName, $technologyName, $cityName)
    {
        $from = ($page - 1) * self::USERS_PER_PAGE;
        $max = self::USERS_PER_PAGE;
        if (!empty($technologyName)) {
            $technology = $this->technologyRepository->findOneBy(['name' => $technologyName]);
            if (empty($technology)) {
                return [];
            }
        } else {
            $technology = null;
        }
        if (!empty($cityName)) {
            $city = $this->cityRepository->findOneBy(['name' => $cityName]);
            if (empty($city)) {
                return [];
            }
        } else {
            $city = null;
        }
        if (!empty($languageName)) {
            $language = $this->languageRepository->findOneBy(['name' => $languageName]);
            if (empty($language)) {
                return [];
            }
        } else {
            $language = null;
        }

        return $this->userRepository->fetchUsersByFilters(
            $from,
            $max,
            $this->convertToSqlSorting($sorting),
            $firstName,
            $lastName,
            $technology,
            $city,
            $language
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