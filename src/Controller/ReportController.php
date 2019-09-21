<?php

namespace App\Controller;

use App\Security\UserProvider;
use App\Services\ReportCreator;
use App\Services\TechnologyCreator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;

class ReportController extends AbstractController
{
    /** @var ReportCreator */
    private $reportCreator;

    /** @var Security */
    private $security;

    /** @var UserProvider */
    private $userProvider;

    /** @var TechnologyCreator */
    private $technologyCreator;

    public function __construct(ReportCreator $reportCreator, Security $security, UserProvider $userProvider, TechnologyCreator $technologyCreator)
    {
        $this->reportCreator = $reportCreator;
        $this->security = $security;
        $this->userProvider = $userProvider;
        $this->technologyCreator = $technologyCreator;
    }

    public function createTechnologyRequestAction(Request $request)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $user = $this->userProvider->loadUserByUsername($this->security->getUser()->getUsername());

        $name = $request->get('name');
        $reason = $request->get('reason');

        $this->technologyCreator->createTechnologyRequest($name, $reason, $user);
        $destination = $request->get('destination');

        switch ($destination)
        {
            case 'user_profile':
                return $this->redirectToRoute('edit_profile', ['username' => $user->getUsername()]);
            case 'project_profile':
                return $this->redirectToRoute('project_profile', ['projectId' => $request->get('projectId')]);
        }

        return $this->redirectToRoute('main');
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