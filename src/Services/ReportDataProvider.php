<?php

namespace App\Services;

use App\Entity\Report;
use App\Repository\ReportRepository;

class ReportDataProvider
{
    /** @var ReportRepository */
    private $reportRepository;

    /**
     * @param ReportRepository $reportRepository
     */
    public function __construct(ReportRepository $reportRepository)
    {
        $this->reportRepository = $reportRepository;
    }

    public function getNewReports()
    {
        return $this->reportRepository->findBy(['options' => Report::REPORT_NEW], null, 50);
    }
}