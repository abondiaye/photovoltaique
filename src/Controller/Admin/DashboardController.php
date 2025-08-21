<?php

namespace App\Controller\Admin;

use App\Repository\LeadRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    #[Route('/admin', name: 'admin_dashboard', methods: ['GET'])]
    public function index(LeadRepository $leadRepository): Response
    {
        try {
            // Compteurs principaux
            $leadsCount = $leadRepository->count([]);
            $newCount   = $leadRepository->count(['status' => 'new']);
            $wonCount   = $leadRepository->count(['status' => 'won']);

            // Derniers leads pour l’aperçu
            $recentLeads = $leadRepository->findBy([], ['createdAt' => 'DESC'], 5);
        } catch (\Throwable $e) {
            // Si la table lead n'existe pas encore, on évite la 500
            $leadsCount = $newCount = $wonCount = 0;
            $recentLeads = [];
        }

        return $this->render('admin/dashboard.html.twig', [
            'leadsCount'  => $leadsCount,
            'newCount'    => $newCount,
            'wonCount'    => $wonCount,
            'recentLeads' => $recentLeads,
        ]);
    }
}
