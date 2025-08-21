<?php

namespace App\Controller;

use App\Entity\Panneau;
use App\Form\Panneau1Type;
use App\Repository\PanneauRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/panneau')]
final class PanneauController extends AbstractController
{
    #[Route(name: 'app_panneau_index', methods: ['GET'])]
    public function index(PanneauRepository $panneauRepository): Response
    {
        return $this->render('panneau/index.html.twig', [
            'panneaus' => $panneauRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_panneau_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $panneau = new Panneau();
        $form = $this->createForm(Panneau1Type::class, $panneau);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($panneau);
            $entityManager->flush();

            return $this->redirectToRoute('app_panneau_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('panneau/new.html.twig', [
            'panneau' => $panneau,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_panneau_show', methods: ['GET'])]
    public function show(Panneau $panneau): Response
    {
        return $this->render('panneau/show.html.twig', [
            'panneau' => $panneau,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_panneau_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Panneau $panneau, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(Panneau1Type::class, $panneau);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_panneau_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('panneau/edit.html.twig', [
            'panneau' => $panneau,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_panneau_delete', methods: ['POST'])]
    public function delete(Request $request, Panneau $panneau, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$panneau->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($panneau);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_panneau_index', [], Response::HTTP_SEE_OTHER);
    }
}
