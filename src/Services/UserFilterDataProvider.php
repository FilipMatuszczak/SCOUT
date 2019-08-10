<?php


namespace App\Services;


use App\Repository\UserRepository;

class UserFilterDataProvider
{
    /** @var UserRepository */
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getUsersByFilters($page, $firstName, $lastName, $sorting)
    {

    }
}