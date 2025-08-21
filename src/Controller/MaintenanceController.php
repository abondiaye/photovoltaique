<?php
// src/Controller/MaintenanceController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MaintenanceController extends AbstractController
{
    #[Route('/maintenance/new', name: 'maintenance_new', methods: ['POST'])]
    public function new(Request $req): Response
    {
        // TODO: enregistrement futur (Lead + MaintenanceRequest)
        $date = $req->request->get('date');
        $this->addFlash('success', sprintf('Demande envoyée pour le %s 👍', $date ?: '—'));

        return $this->redirectToRoute('app_home');
    }
}
