<?php

namespace App\Controller;

use App\Services\ReportCreator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class ReportController extends AbstractController
{
    /** @var ReportCreator */
    private $reportCreator;

    public function __construct(ReportCreator $reportCreator)
    {
        $this->reportCreator = $reportCreator;
    }

    public function reportPostAction(Request $request)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $reason = $request->get('reason');
        $postId = $request->get('postId');

        $this->reportCreator->createReportForPost($postId, $reason);
        $destination = $request->get('destination');
        switch ($destination)
        {
            case 'user_profile':
                return $this->redirectToRoute('user_profile', ['username' => $request->get('username')]);
            case 'project_profile':
                return $this->redirectToRoute('project_profile', ['projectId' => $request->get('projectId')]);
        }

        return $this->redirectToRoute('main');
    }
}