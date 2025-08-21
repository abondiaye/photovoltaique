<?php

namespace App\Controller\Admin;

use App\Entity\MaintenanceRequest;
use App\Repository\MaintenanceRequestRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/maintenance')]
class MaintenanceAdminController extends AbstractController
{
    #[Route('/', name: 'admin_maintenance_index', methods: ['GET'])]
    public function index(MaintenanceRequestRepository $repo): Response
    {
        $items = $repo->findBy([], ['createdAt' => 'DESC'], 50);

        return $this->render('admin/maintenance/index.html.twig', [
            'items' => $items,
        ]);
    }

    #[Route('/{id}', name: 'admin_maintenance_show', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function show(MaintenanceRequest $request): Response
    {
        return $this->render('admin/maintenance/show.html.twig', [
            'request' => $request,
        ]);
    }
}
