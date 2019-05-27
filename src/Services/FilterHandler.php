<?php


namespace App\Services;


use App\Repository\CityRepository;
use App\Repository\CountryRepository;
use App\Repository\LanguageRepository;
use App\Repository\TechnologyRepository;

class FilterHandler
{
    /** @var CityRepository */
    private $cityRepository;

    /** @var CountryRepository */
    private $countryRepository;

    /** @var LanguageRepository */
    private $languageRepository;

    /** @var TechnologyRepository */
    private $technologyRepository;

    public function __construct(
        TechnologyRepository $technologyRepository,
        CityRepository $cityRepository,
        CountryRepository $countryRepository,
        LanguageRepository $languageRepository
    )
    {
        $this->cityRepository = $cityRepository;
        $this->technologyRepository = $technologyRepository;
        $this->countryRepository = $countryRepository;
        $this->languageRepository = $languageRepository;
    }

    public function getCityNameFilter($prefix)
    {

    }
}