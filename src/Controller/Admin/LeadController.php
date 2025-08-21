<?php

namespace App\Controller\Admin;

use App\Entity\Lead;
use App\Repository\LeadRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/lead')]
class LeadController extends AbstractController
{
    #[Route('/', name: 'admin_leads', methods: ['GET'])]
    public function index(Request $request, LeadRepository $repo): Response
    {
        $status = $request->query->get('status');
        $criteria = $status ? ['status' => $status] : [];

        $leads = $repo->findBy($criteria, ['createdAt' => 'DESC'], 100);

        return $this->render('admin/lead/index.html.twig', [
            'leads'  => $leads,
            'status' => $status,
        ]);
    }

    #[Route('/{id}', name: 'admin_lead_show', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function show(Lead $lead): Response
    {
        return $this->render('admin/lead/show.html.twig', [
            'lead' => $lead,
        ]);
    }

    #[Route('/{id}/status', name: 'admin_lead_status', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function updateStatus(Lead $lead, Request $request, EntityManagerInterface $em): Response
    {
        $newStatus = $request->request->get('status', 'new');
        $lead->setStatus($newStatus);
        $em->flush();

        $this->addFlash('success', 'Statut mis à jour.');
        return $this->redirectToRoute('admin_lead_show', ['id' => $lead->getId()]);
    }

    #[Route('/{id}/delete', name: 'admin_lead_delete', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function delete(Lead $lead, EntityManagerInterface $em, Request $request): Response
    {
        // (Optionnel) vérifier un token CSRF si tu l’ajoutes dans le formulaire
        // if (!$this->isCsrfTokenValid('delete_lead_'.$lead->getId(), $request->request->get('_token'))) {
        //     throw $this->createAccessDeniedException('Token invalide.');
        // }

        $em->remove($lead);
        $em->flush();

        $this->addFlash('success', 'Lead supprimé.');
        return $this->redirectToRoute('admin_leads');
    }
}
