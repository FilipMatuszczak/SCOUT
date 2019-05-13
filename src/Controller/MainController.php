<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MainController extends AbstractController
{


    public function mainAction()
    {
        return $this->render('main/main.html.twig', []);
    }
}