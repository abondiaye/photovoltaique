<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Repository\LeadRepository;
use App\Repository\MaintenanceRequestRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin')]
final class DashboardController extends AbstractController
{
    #[Route('', name: 'admin_dashboard', methods: ['GET'])]
    public function index(
        LeadRepository $leadRepo,
        MaintenanceRequestRepository $mrRepo
    ): Response {
        $recentLeads = $leadRepo->findBy([], ['createdAt' => 'DESC'], 5);
        $recentRequests = $mrRepo->findBy([], ['createdAt' => 'DESC'], 5);

        $stats = [
            'leads' => $leadRepo->count([]),
            'requests' => $mrRepo->count([]),
        ];

        return $this->render('admin/dashboard.html.twig', [
            'recentLeads' => $recentLeads,
            'recentRequests' => $recentRequests,
            'stats' => $stats,
        ]);
    }
}
