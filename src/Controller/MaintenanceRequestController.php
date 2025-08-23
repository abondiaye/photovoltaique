<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\MaintenanceRequest;
use App\Form\MaintenanceRequestType;
use App\Repository\MaintenanceRequestRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/maintenance')]
final class MaintenanceRequestController extends AbstractController
{
    #[Route('/', name: 'app_maintenance_request_index', methods: ['GET'])]
    public function index(MaintenanceRequestRepository $repository): Response
    {
        return $this->render('maintenance_request/index.html.twig', [
            'requests' => $repository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_maintenance_request_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $maintenanceRequest = new MaintenanceRequest();
        $form = $this->createForm(MaintenanceRequestType::class, $maintenanceRequest);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($maintenanceRequest);
            $em->flush();

            return $this->redirectToRoute('app_maintenance_request_index');
        }

        return $this->render('maintenance_request/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_maintenance_request_show', methods: ['GET'])]
    public function show(MaintenanceRequest $maintenanceRequest): Response
    {
        return $this->render('maintenance_request/show.html.twig', [
            'request' => $maintenanceRequest,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_maintenance_request_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, MaintenanceRequest $maintenanceRequest, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(MaintenanceRequestType::class, $maintenanceRequest);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            return $this->redirectToRoute('app_maintenance_request_index');
        }

        return $this->render('maintenance_request/edit.html.twig', [
            'form' => $form->createView(),
            'request' => $maintenanceRequest,
        ]);
    }

    #[Route('/{id}', name: 'app_maintenance_request_delete', methods: ['POST'])]
    public function delete(Request $request, MaintenanceRequest $maintenanceRequest, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete'.$maintenanceRequest->getId(), $request->request->getString('_token'))) {
            $em->remove($maintenanceRequest);
            $em->flush();
        }

        return $this->redirectToRoute('app_maintenance_request_index');
    }
}
