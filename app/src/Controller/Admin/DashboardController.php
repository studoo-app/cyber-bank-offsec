<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\CaptureTheFlagService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    public function __construct(
        private readonly CaptureTheFlagService $service
    )
    {
    }

    #[Route('/admin/dashboard', name: 'app_admin_dashboard')]
    public function index(UserRepository $repository): Response
    {
        return $this->render('admin/dashboard/index.html.twig', [
            'clients' => $repository->findClients(),
            'flags'=>$this->service->getFlags()
        ]);
    }

    #[Route('/admin/dashboard/client-overview/{id}', name: 'app_admin_dashboard_client_overview')]
    public function clientOverview(User $user): Response
    {
        return $this->render('admin/dashboard/client_account.html.twig', [
            'client' => $user,
        ]);
    }
}
