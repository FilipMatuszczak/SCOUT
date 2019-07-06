<?php

namespace App\Controller;

use App\Repository\CityRepository;
use App\Repository\CountryRepository;
use App\Repository\LanguageRepository;
use App\Repository\ProjectRepository;
use App\Repository\TechnologyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

/** */
class FilterController extends AbstractController
{
    /** @var CityRepository */
    private $cityRepository;

    /** @var CountryRepository */
    private $countryRepository;

    /** @var LanguageRepository */
    private $languagueRepository;

    /** @var ProjectRepository */
    private $projectRepository;

    /** @var TechnologyRepository */
    private $technologyRepository;

    /**
     * @param CityRepository $cityRepository
     * @param TechnologyRepository $technologyRepository
     * @param ProjectRepository $projectRepository
     * @param CountryRepository $countryRepository
     * @param LanguageRepository $languageRepository
     */
    public function __construct(
        CityRepository $cityRepository,
        TechnologyRepository $technologyRepository,
        ProjectRepository $projectRepository,
        CountryRepository $countryRepository,
        LanguageRepository $languageRepository
    )
    {
        $this->cityRepository = $cityRepository;
        $this->countryRepository = $countryRepository;
        $this->languagueRepository = $languageRepository;
        $this->projectRepository = $projectRepository;
        $this->technologyRepository = $technologyRepository;
    }

    /**
     * @param string $prefix
     * @param int    $limit
     *
     * @return JsonResponse
     */
    public function getCityNameFilterAction($prefix, $limit)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $names = $this->cityRepository->fetchSimilarNamesByPrefix($prefix, $limit);

        return new JsonResponse([
            'names' => $names,
        ]);
    }

    /**
     * @param $limit
     * @param $prefix
     *
     * @return JsonResponse
     */
    public function getCountryNameFilterAction($limit, $prefix)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $names = $this->countryRepository->fetchSimilarNamesByPrefix($prefix, $limit);

        return new JsonResponse([
            'names' => $names,
        ]);
    }

    /**
     * @param $limit
     * @param $prefix
     *
     * @return JsonResponse
     */
    public function getLanguageNameFilterAction($limit, $prefix)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $names = $this->languagueRepository->fetchSimilarNamesByPrefix($prefix, $limit);

        return new JsonResponse([
            'names' => $names,
        ]);
    }

    /**
     * @param $limit
     * @param $prefix
     *
     * @return JsonResponse
     */
    public function getProjectNameFilterAction($limit, $prefix)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $names = $this->projectRepository->fetchSimilarNamesByPrefix($prefix, $limit);

        return new JsonResponse([
            'names' => $names,
        ]);
    }

    /**
     * @param $limit
     * @param $prefix
     *
     * @return JsonResponse
     */
    public function getTechnologyNameFilterAction($limit, $prefix)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $names = $this->technologyRepository->fetchSimilarNamesByPrefix($prefix, $limit);

        return new JsonResponse([
            'names' => $names,
        ]);
    }
}