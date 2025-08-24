<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Repository\LeadRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/leads')]
final class LeadController extends AbstractController
{
    #[Route('', name: 'admin_lead_index', methods: ['GET'])]
    public function index(LeadRepository $repo): Response
    {
        return $this->render('admin/lead/index.html.twig', [
            'leads' => $repo->findBy([], ['createdAt' => 'DESC'], 100),
        ]);
    }
}
