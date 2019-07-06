<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

/** */
class MainController extends AbstractController
{
    /**
     * @return Response
     */
    public function mainAction()
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        return $this->render('main/main.html.twig', []);
    }
}