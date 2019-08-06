<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProjectsController extends AbstractController
{
    public function indexAction()
    {
        return $this->render('main/search_projects.html.twig');
    }
}