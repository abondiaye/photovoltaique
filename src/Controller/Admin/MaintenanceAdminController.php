<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Repository\MaintenanceRequestRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/maintenance')]
final class MaintenanceAdminController extends AbstractController
{
    #[Route('', name: 'admin_maintenance_index', methods: ['GET'])]
    public function index(MaintenanceRequestRepository $repo): Response
    {
        return $this->render('admin/maintenance/index.html.twig', [
            'requests' => $repo->findAll(),
        ]);
    }
}
