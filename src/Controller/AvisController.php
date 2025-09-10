<?php
namespace App\Controller;

use App\Entity\Avis;
use App\Repository\AvisRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AvisController extends AbstractController
{
    #[Route('/avis', name: 'avis', methods: ['GET'])]
    public function index(AvisRepository $avisRepository): Response
    {
        $avis = $avisRepository->findBy([], ['createdAt' => 'DESC']);

        return $this->render('avis/avis.html.twig', [
            'avis' => $avis,
        ]);
    }

    #[Route('/avis/new', name: 'avis_new', methods: ['POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $nom = $request->request->get('nom');
        $note = (int) $request->request->get('note');
        $message = $request->request->get('message');

        $avis = new Avis();
        $avis->setNom($nom);
        $avis->setNote($note);
        $avis->setMessage($message);
        $avis->setCreatedAt(new \DateTimeImmutable());

        $em->persist($avis);
        $em->flush();

        $this->addFlash('success', 'Merci pour votre avis !');

        return $this->redirectToRoute('avis');
    }
}