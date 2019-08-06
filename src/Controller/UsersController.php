<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UsersController extends AbstractController
{
    public function indexAction()
    {
        return $this->render('main/search_users.html.twig');
    }
}