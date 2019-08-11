<?php

namespace App\Controller;

use App\Services\UserFilterDataProvider;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class UsersController extends AbstractController
{
    /** @var UserFilterDataProvider */
    private $userFilterDataProvier;

    public function __construct(UserFilterDataProvider $userFilterDataProvier)
    {
        $this->userFilterDataProvier = $userFilterDataProvier;
    }

    public function indexAction(Request $request)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $page = $request->get('page');
        if (!isset($page))
        {
            $page = 1;
        }
        $firstName = $request->get('firstName');
        $lastName = $request->get('lastName');
        $sorting = $request->get('sorting');
        if (!isset($sorting))
        {
            $sorting = 'A-Z';
        }

        $technology = !empty($request->get('technology')) ? $request->get('technology') : '';
        $language = !empty($request->get('language')) ? $request->get('language') : '';
        $city = !empty($request->get('city')) ? $request->get('city') : '';

        $users = $this->userFilterDataProvier->getUsersByFilters($page, $firstName, $lastName, $sorting, $language, $technology, $city);

        return $this->render('main/search_users.html.twig', ['users' => $users, 'page' => $page]);
    }
}