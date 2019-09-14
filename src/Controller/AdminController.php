<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminController extends AbstractController
{
    public function adminReportsIndexAction()
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        return $this->render('main/admin-reports.html.twig');
    }
}