<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\AvailabilitySlot;
use App\Form\AvailabilitySlotType;
use App\Repository\AvailabilitySlotRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/availability')]
final class AvailabilityAdminController extends AbstractController
{
    #[Route('', name: 'admin_availability_index', methods: ['GET'])]
    public function index(AvailabilitySlotRepository $repo): Response
    {
        return $this->render('admin/availability/index.html.twig', [
            'slots' => $repo->findBy([], ['startAt' => 'ASC']),
        ]);
    }

    #[Route('/new', name: 'admin_availability_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $slot = new AvailabilitySlot(new \DateTimeImmutable('+1 day 09:00'), new \DateTimeImmutable('+1 day 10:00'), 1);
        $form = $this->createForm(AvailabilitySlotType::class, $slot)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($slot);
            $em->flush();
            $this->addFlash('success', 'Créneau créé.');
            return $this->redirectToRoute('admin_availability_index');
        }

        return $this->render('admin/availability/new.html.twig', ['form' => $form->createView()]);
    }

    #[Route('/{id}/edit', name: 'admin_availability_edit', methods: ['GET', 'POST'])]
    public function edit(AvailabilitySlot $slot, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(AvailabilitySlotType::class, $slot)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Créneau mis à jour.');
            return $this->redirectToRoute('admin_availability_index');
        }

        return $this->render('admin/availability/edit.html.twig', [
            'form' => $form->createView(),
            'slot' => $slot,
        ]);
    }

    #[Route('/{id}', name: 'admin_availability_delete', methods: ['POST'])]
    public function delete(AvailabilitySlot $slot, Request $request, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete'.$slot->getId(), $request->request->getString('_token'))) {
            $em->remove($slot);
            $em->flush();
            $this->addFlash('success', 'Créneau supprimé.');
        }
        return $this->redirectToRoute('admin_availability_index');
    }
}
