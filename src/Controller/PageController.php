<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PageController extends AbstractController
{
    #[Route('/features', name: 'features')]
    public function features(): Response
    {
        return $this->render('pages/features.html.twig');
    }

    #[Route('/devis', name: 'devis')]
    public function devis(): Response
    {
        return $this->render('pages/devis.html.twig');
    }

    #[Route('/realisations', name: 'realisations')]
    public function realisations(): Response
    {
        return $this->render('pages/realisations.html.twig');
    }

    #[Route('/contact', name: 'contact')]
    public function contact(): Response
    {
        return $this->render('pages/contact.html.twig');
    }
}