<?php

namespace App\Services;

use App\Entity\User;
use App\Repository\CityRepository;
use App\Repository\LanguageRepository;
use App\Repository\TechnologyRepository;
use App\Repository\UserRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\File;

/** */
class ProfileEditHandler
{
    /** @var UserRepository $userRepository */
    private $userRepository;

    /** @var EntityManagerInterface $entityManager */
    private $entityManager;

    /** @var CityRepository */
    private $cityRepository;

    /** @var TechnologyRepository */
    private $technologyRepository;

    /** @var LanguageRepository */
    private $languageRepository;

    /**
     * @param UserRepository $userRepository
     * @param EntityManagerInterface $entityManager
     * @param CityRepository $cityRepository
     * @param TechnologyRepository $technologyRepository
     * @param LanguageRepository $languageRepository
     */
    public function __construct(
        UserRepository $userRepository,
        EntityManagerInterface $entityManager,
        CityRepository $cityRepository,
        TechnologyRepository $technologyRepository,
        LanguageRepository $languageRepository
    )
    {
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;
        $this->cityRepository = $cityRepository;
        $this->technologyRepository = $technologyRepository;
        $this->languageRepository = $languageRepository;
    }

    /**
     * @param User $user
     * @param File $file
     */
    public function saveNewProfilePhoto(User $user, File $file)
    {
        if (null === $file) {
            return;
        }

        $strm = fopen($file->getRealPath(), 'rb');

        $user->setPhoto(stream_get_contents($strm));

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    /**
     * @param User $user
     * @param $firstname
     * @param $lastname
     * @param $birthDate
     * @param $info
     * @param $newsletter
     */
    public function saveBasicInfo(User $user, $firstname, $lastname, $birthDate, $info, $newsletter)
    {
        $user->setFirstname($firstname);
        $user->setLastname($lastname);
        $user->setInfo($info);
        $user->setDateOfBirth($birthDate);
        if ($newsletter) {
            $user->setOptions($user->getOptions() | User::USER_NEWSLETTER_ON);
        } else {
            $user->setOptions($user->getOptions() &~ User::USER_NEWSLETTER_ON);
        }

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    public function updateTechnologiesForUser(User $user, $technologyNames)
    {
        var_dump($technologyNames);
        exit;

        foreach ($user->getTechnology() as $technology)
        {
            $user->removeTechnology($technology);
        }
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $technologies = $this->technologyRepository->findBy(['name' => $technologyNames]);

        foreach ($technologies as $technology) {
            $user->addTechnology($technology);
        }

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    public function updateLanguagesForUser(User $user, $languageNames)
    {
        foreach ($user->getLanguage() as $language)
        {
            $user->removeLanguage($language);
        }
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $languages = $this->languageRepository->findBy(['name' => $languageNames]);

        foreach ($languages as $language) {
            $user->addLanguage($language);
        }

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    public function updateCitiesForUser(User $user, $cityNames)
    {
        foreach ($user->getCity() as $city)
        {
            $user->removeCity($city);
        }
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $cities = $this->cityRepository->findBy(['name' => $cityNames]);

        foreach ($cities as $city) {
            $user->addCity($city);
        }

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}