<?php

namespace App\Controller;

use App\Entity\Report;
use App\Services\ReportCreator;
use App\Services\ReportDataProvider;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminController extends AbstractController
{
    /** @var ReportDataProvider */
    private $reportDataProvider;

    /** @var ReportCreator */
    private $reportCreator;

    public function __construct(ReportDataProvider $reportDataProvider, ReportCreator $reportCreator)
    {
        $this->reportDataProvider = $reportDataProvider;
        $this->reportCreator = $reportCreator;
    }

    public function adminReportsIndexAction()
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $newReports = $this->reportDataProvider->getNewReports();

        return $this->render('main/admin-reports.html.twig', ['reports' => $newReports]);
    }

    public function adminTechnologiesIndexAction()
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        return $this->render('main/admin-technologies.html.twig');
    }

    public function cancelReportAction(Request $request, $reportId)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $this->reportCreator->updateReport($reportId, Report::REPORT_DECLINED);

        //return $this->redirectToRoute('admin_reports');
        return new Response('Report cancelled successfully', Response::HTTP_OK);

    }

}