<?php
namespace App\Controller;

use App\Entity\Lead;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class LeadController extends AbstractController
{
    #[Route('/lead/new', name: 'lead_new', methods: ['POST'])]
    public function new(Request $request, EntityManagerInterface $em)
    {
        $lead = new Lead();
        $lead->setFullName($request->get('fullName'));
        $lead->setEmail($request->get('email'));
        $lead->setPhone($request->get('phone'));
        $lead->setRoofAreaM2($request->get('roofAreaM2'));
        $lead->setHasShadow((bool)$request->get('hasShadow'));
        $em->persist($lead); $em->flush();
        $this->addFlash('success', 'Merci ! Nous revenons vers vous très vite.');
        return $this->redirectToRoute('app_home');
    }
}