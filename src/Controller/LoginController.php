<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/** */
class LoginController extends AbstractController
{
    /** @var UserRepository */
    private $userRepository;

    /**
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /** */
    public function indexAction()
    {
        return $this->render('main/main.html.twig',[]);
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function createUserAction(Request $request)
    {
        $newUserData = json_decode(
            $request->getContent(),
            true
        );

        $email = $this->userRepository->find(1)->getEmail();
        
        return new JsonResponse(
            [
                'status' => 'ok',
                'email' => $email,
            ],
            JsonResponse::HTTP_CREATED
        );
    }
}