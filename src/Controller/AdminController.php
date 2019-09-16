<?php

namespace App\Controller;

use App\Entity\Report;
use App\Repository\ReportRepository;
use App\Services\MessageCreator;
use App\Services\PostCreator;
use App\Services\ReportCreator;
use App\Services\ReportDataProvider;
use App\Services\UserHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminController extends AbstractController
{
    /** @var ReportDataProvider */
    private $reportDataProvider;

    /** @var ReportCreator */
    private $reportCreator;

    /** @var UserHandler */
    private $userHandler;

    /** @var MessageCreator */
    private $messageCreator;

    /** @var PostCreator */
    private $postCreator;

    /** @var ReportRepository */
    private $reportRepository;

    public function __construct(
        ReportDataProvider $reportDataProvider,
        ReportCreator $reportCreator,
        UserHandler $userHandler,
        MessageCreator $messageCreator,
        PostCreator $postCreator,
        ReportRepository $reportRepository
    )
    {
        $this->reportDataProvider = $reportDataProvider;
        $this->reportCreator = $reportCreator;
        $this->userHandler = $userHandler;
        $this->messageCreator = $messageCreator;
        $this->postCreator = $postCreator;
        $this->reportRepository = $reportRepository;
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

    public function deletePostAction(Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $userId = $request->get('userId');
        $reportId = $request->get('reportId');

        if ($request->get('checkbox'))
        {
            $this->userHandler->banUser($userId);
        }

        $postId = $this->reportRepository->findOneBy(['reportId' => $reportId])->getPost()->getPostId();
        $this->reportCreator->deleteReport($reportId);
        $this->messageCreator->createMessage($userId, $request->get('text'));
        $this->postCreator->deletePost($postId);

        return $this->redirectToRoute('admin_reports');
    }

    public function banUserAction(Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $userId = $request->get('userId');
        $reportId = $request->get('reportId');

        $this->userHandler->banUser($userId);
        $this->reportCreator->updateReport($reportId, Report::REPORT_ACCEPTED);
        $this->reportCreator->changeReason($reportId, $request->get('text'));

        return $this->redirectToRoute('admin_reports');
    }
}