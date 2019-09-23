<?php


namespace App\Controller;

use App\Entity\City;
use App\Repository\CityRepository;
use App\Repository\LanguageRepository;
use App\Repository\PostRepository;
use App\Repository\TechnologyRepository;
use App\Security\UserProvider;
use App\Services\PostCreator;
use App\Services\ProfileEditHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Security;

class ProfileController extends AbstractController
{
    /** @var UserProvider */
    private $userProvider;

    /** @var PostCreator */
    private $postCreator;

    /** @var Security */
    private $security;

    /** @var PostRepository */
    private $postRepository;

    /** @var ProfileEditHandler */
    private $profileEditHandler;

    /** @var TechnologyRepository */
    private $technologyRepository;

    /** @var LanguageRepository */
    private $languageRepository;

    /** @var CityRepository */
    private $cityRepository;

    public function __construct(
        UserProvider $userProvider,
        PostCreator $postCreator,
        Security $security,
        PostRepository $postRepository,
        ProfileEditHandler $profileEditHandler,
        TechnologyRepository $technologyRepository,
        LanguageRepository $languageRepository,
        CityRepository $cityRepository
    )
    {
        $this->userProvider = $userProvider;
        $this->postCreator = $postCreator;
        $this->security = $security;
        $this->postRepository = $postRepository;
        $this->profileEditHandler = $profileEditHandler;
        $this->technologyRepository = $technologyRepository;
        $this->languageRepository = $languageRepository;
        $this->cityRepository = $cityRepository;
    }

    public function profileIndexAction($username)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $user = $this->userProvider->loadUserByUsername($username);
        if (!$user)
        {
            throw new NotFoundHttpException();
        }
        if ($user->getDateOfBirth()) {
            $userAge = date_diff(date_create($user->getDateOfBirth()->format('Y-m-d H:i:s')), date_create('today'))->y;
        } else {
            $userAge = '';
        }
        $posts = $this->postRepository->fetchUserWallPosts($user);

        if ($photo = $user->getPhoto()) {
            $photo = stream_get_contents($photo);
        }

        return $this->render('main/profile.html.twig',
            [
                'user' => $user,
                'age' => $userAge,
                'posts' => $posts,
                'img' => base64_encode($photo),
            ]
        );
    }

    public function saveCitiesForUserAction(Request $request)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $username = $this->security->getUser()->getUsername();

        $user = $this->userProvider->loadUserByUsername($username);
        $cities = $request->get('cities');

        $this->profileEditHandler->updateCitiesForUser($user, $cities);

        return $this->redirectToRoute('edit_profile', ['username' => $username]);
    }

    public function saveLanguagesForUserAction(Request $request)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $username = $this->security->getUser()->getUsername();

        $user = $this->userProvider->loadUserByUsername($username);
        $languages = $request->get('languages');

        $this->profileEditHandler->updateLanguagesForUser($user, $languages);

        return $this->redirectToRoute('edit_profile', ['username' => $username]);
    }

    public function saveTechnologiesForUserAction(Request $request)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $username = $this->security->getUser()->getUsername();

        $user = $this->userProvider->loadUserByUsername($username);
        $technologies = $request->get('technologies');

        $this->profileEditHandler->updateTechnologiesForUser($user, $technologies);

        return $this->redirectToRoute('edit_profile', ['username' => $username]);
    }

    public function createPostOnWall(Request $request)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $username = $this->security->getUser()->getUsername();

        $user = $this->userProvider->loadUserByUsername($username);
        $photoFile = $request->files->get('photo');
        $text = $request->get('text');
        $this->postCreator->createPostForUser($user, strip_tags($text), $photoFile);

        return $this->redirectToRoute('user_profile', ['username' => $request->get('username')]);
    }

    public function editBasicInfoAction(Request $request, $username)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        if (!$user = $this->canEdit($username)) {
            return $this->redirectToRoute('main');
        }

        $newsletter = $request->get('newsletter');
        $firstname = $request->get('firstname');
        $lastname = $request->get('lastname');
        $birthDate = new \DateTime($request->get('bdaytime'));
        $info = $request->get('info');

        $this->profileEditHandler->saveBasicInfo($user, $firstname, $lastname, $birthDate, $info, $newsletter);

        return $this->redirectToRoute('user_profile', ['username' => $request->get('username')]);
    }

    public function editUserPhoto(Request $request, $username)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $photoFile = $request->files->get('fileToUpload');

        if (!$user = $this->canEdit($username)) {
            return $this->redirectToRoute('main');
        }

        $this->profileEditHandler->saveNewProfilePhoto($user, $photoFile);

        return $this->redirectToRoute('user_profile', ['username' => $request->get('username')]);
    }

    public function editIndexAction($username)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        if (!$user = $this->canEdit($username)) {
            return $this->redirectToRoute('main');
        }

        if ($user->getPhoto()) {
            $photo = stream_get_contents($user->getPhoto());
        } else {
            $photo = null;
        }

        return $this->render('main/edit-user.html.twig', ['user' => $user, 'img' => base64_encode($photo)]);
    }

    public function getUserTechnologiesAction($userId)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $user = $this->userProvider->loadUserById($userId);
        $technologyNames = [];

        foreach ($user->getTechnology() as $technology) {
            $technologyNames[] = $technology->getName();
        }

        return new JsonResponse($technologyNames);
    }

    public function getUserLanguagesAction($userId)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $user = $this->userProvider->loadUserById($userId);
        $languagesNames = [];

        foreach ($user->getLanguage() as $language) {
            $languagesNames[] = $language->getName();
        }

        return new JsonResponse($languagesNames);
    }

    public function getUserCitiesAction($userId)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $user = $this->userProvider->loadUserById($userId);

        $cityNames = [];

        foreach ($user->getCity() as $city) {
            $cityNames[] = $city->getName();
        }

        return new JsonResponse($cityNames);
    }

    public function technologyExistsAction($technologyName)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $technology = $this->technologyRepository->findOneBy(['name' => $technologyName]);

        if ($technology)
        {
            return new JsonResponse([true]);
        }

        return new JsonResponse([false]);
    }

    public function languageExistsAction($languageName)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $language = $this->languageRepository->findOneBy(['name' => $languageName]);

        if ($language)
        {
            return new JsonResponse([true]);
        }

        return new JsonResponse([false]);
    }


    public function cityExistsAction($cityName)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $city = $this->cityRepository->findOneBy(['name' => $cityName]);

        if ($city)
        {
            return new JsonResponse([true]);
        }

        return new JsonResponse([false]);
    }

    private function canEdit($username)
    {
        $currentUsername = $user = $this->security->getUser()->getUsername();

        if ($currentUsername !== $username) {
            return null;
        }

        return $this->userProvider->loadUserByUsername($username);
    }
}