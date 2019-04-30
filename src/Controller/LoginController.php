<?php

namespace App\Controller;

use App\Services\RegisterHandler;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/** */
class LoginController extends AbstractController
{
    /** @var RegisterHandler */
    private $registerHandler;

    /**
     * @param RegisterHandler $registerHandler
     */
    public function __construct(RegisterHandler $registerHandler)
    {
        $this->registerHandler = $registerHandler;
    }

    /** */
    public function indexAction()
    {
        return $this->render('main/main.html.twig', []);
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

        try {
            $this->registerHandler->registerUser($newUserData);
        } catch (OptimisticLockException $e) {
            return new JsonResponse(
                [
                    'status' => 'failed',
                ],
                JsonResponse::HTTP_LOCKED
            );
        } catch (ORMException $e) {
            return new JsonResponse(
                [
                    'status' => 'failed',
                ],
                JsonResponse::HTTP_BAD_GATEWAY
            );
        }

        return new JsonResponse(
            [
                'status' => 'ok',
            ],
            JsonResponse::HTTP_CREATED
        );
    }
}