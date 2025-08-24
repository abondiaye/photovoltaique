<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\MaintenanceRequest;
use App\Form\MaintenanceRequestType;
use App\Repository\MaintenanceRequestRepository;
use App\Repository\AvailabilitySlotRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/maintenance')]
final class MaintenanceRequestController extends AbstractController
{
    #[Route('/', name: 'app_maintenance_request_index', methods: ['GET'])]
    public function index(MaintenanceRequestRepository $repository): Response
    {
        return $this->render('maintenance_request/index.html.twig', [
            'requests' => $repository->findBy([], ['createdAt' => 'DESC']),
        ]);
    }

    #[Route('/new', name: 'app_maintenance_request_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        EntityManagerInterface $em,
        AvailabilitySlotRepository $availabilitySlots,
        MailerInterface $mailer
    ): Response {
        $maintenanceRequest = new MaintenanceRequest();
        $form = $this->createForm(MaintenanceRequestType::class, $maintenanceRequest);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $slot = $availabilitySlots->findCovering($maintenanceRequest->getRequestedDate());

            if ($slot === null || !$slot->hasRemaining()) {
                $this->addFlash('danger', 'Ce créneau n’est plus disponible. Choisissez un autre horaire.');
                return $this->render('maintenance_request/new.html.twig', ['form' => $form->createView()]);
            }

            $slot->bookOne();
            $em->persist($slot);
            $em->persist($maintenanceRequest);
            $em->flush();

            $email = (new TemplatedEmail())
                ->to($maintenanceRequest->getEmail())
                ->subject('Votre demande de maintenance - confirmation de réception')
                ->htmlTemplate('emails/maintenance_created.html.twig')
                ->context([
                    'name' => $maintenanceRequest->getFullName(),
                    'date' => $maintenanceRequest->getRequestedDate(),
                ]);
            $mailer->send($email);

            $this->addFlash('success', 'Demande enregistrée. Un email de confirmation a été envoyé.');
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
    public function edit(
        Request $request,
        MaintenanceRequest $maintenanceRequest,
        EntityManagerInterface $em
    ): Response {
        $form = $this->createForm(MaintenanceRequestType::class, $maintenanceRequest);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Demande mise à jour.');
            return $this->redirectToRoute('app_maintenance_request_index');
        }

        return $this->render('maintenance_request/edit.html.twig', [
            'form' => $form->createView(),
            'request' => $maintenanceRequest,
        ]);
    }

    #[Route('/{id}', name: 'app_maintenance_request_delete', methods: ['POST'])]
    public function delete(
        Request $request,
        MaintenanceRequest $maintenanceRequest,
        EntityManagerInterface $em
    ): Response {
        if ($this->isCsrfTokenValid('delete'.$maintenanceRequest->getId(), $request->request->getString('_token'))) {
            $em->remove($maintenanceRequest);
            $em->flush();
            $this->addFlash('success', 'Demande supprimée.');
        }

        return $this->redirectToRoute('app_maintenance_request_index');
    }
}
