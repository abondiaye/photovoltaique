<?php

namespace App\Controller;

use App\Repository\RealisationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RealisationController extends AbstractController
{
    #[Route('/realisations', name: 'app_realisations')]
    public function index(RealisationRepository $realisationRepository): Response
    {
        $realisations = $realisationRepository->findAll();

        return $this->render('realisation/index.html.twig', [
            'realisations' => $realisations,
        ]);
    }
}
