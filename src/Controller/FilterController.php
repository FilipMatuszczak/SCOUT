<?php


namespace App\Controller;


use App\Repository\CityRepository;
use App\Repository\CountryRepository;
use App\Repository\LanguageRepository;
use App\Repository\ProjectRepository;
use App\Repository\TechnologyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

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

    public function getCityNameFilterAction($prefix, $limit)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $names = $this->cityRepository->fetchSimilarNamesByPrefix($prefix, $limit);

        return new JsonResponse([
            'names' => $names,
        ]);
    }

    public function getCountryNameFilterAction($limit, $prefix)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $names = $this->countryRepository->fetchSimilarNamesByPrefix($prefix, $limit);

        return new JsonResponse([
            'names' => $names,
        ]);
    }

    public function getLanguageNameFilterAction($limit, $prefix)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $names = $this->languagueRepository->fetchSimilarNamesByPrefix($prefix, $limit);

        return new JsonResponse([
            'names' => $names,
        ]);
    }

    public function getProjectNameFilterAction($limit, $prefix)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $names = $this->projectRepository->fetchSimilarNamesByPrefix($prefix, $limit);

        return new JsonResponse([
            'names' => $names,
        ]);
    }

    public function getTechnologyNameFilterAction($limit, $prefix)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $names = $this->technologyRepository->fetchSimilarNamesByPrefix($prefix, $limit);

        return new JsonResponse([
            'names' => $names,
        ]);
    }
}