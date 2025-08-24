<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\MaintenanceRequest;
use App\Repository\MaintenanceRequestRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/calendar')]
final class CalendarController extends AbstractController
{
    #[Route('', name: 'admin_calendar', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('admin/calendar/index.html.twig');
    }

    #[Route('/events', name: 'admin_calendar_events', methods: ['GET'])]
    public function events(MaintenanceRequestRepository $repo): JsonResponse
    {
        // événements pour le calendrier (ex: uniquement confirmés)
        $requests = $repo->findBy([], ['requestedDate' => 'ASC'], 500);
        $events = array_map(static function (MaintenanceRequest $r): array {
            return [
                'id' => $r->getId(),
                'title' => sprintf('%s (%s)', $r->getFullName(), $r->getCity()),
                'start' => $r->getRequestedDate()->format('c'),
                'color' => match ($r->getStatus()) {
                    MaintenanceRequest::STATUS_CONFIRMED => '#0d6efd',
                    MaintenanceRequest::STATUS_DONE => '#198754',
                    MaintenanceRequest::STATUS_CANCELLED => '#dc3545',
                    default => '#6c757d',
                },
            ];
        }, $requests);

        return new JsonResponse($events);
    }

    #[Route('/accept/{id}', name: 'admin_calendar_accept', methods: ['POST'])]
    public function accept(
        MaintenanceRequest $requestEntity,
        EntityManagerInterface $em,
        MailerInterface $mailer
    ): Response {
        $requestEntity->setStatus(MaintenanceRequest::STATUS_CONFIRMED);
        $em->flush();

        $email = (new TemplatedEmail())
            ->to($requestEntity->getEmail())
            ->subject('Votre intervention photovoltaïque est confirmée')
            ->htmlTemplate('emails/maintenance_confirm.html.twig')
            ->context([
                'name' => $requestEntity->getFullName(),
                'date' => $requestEntity->getRequestedDate(),
            ]);

        $mailer->send($email);

        return $this->json(['ok' => true]);
    }

    #[Route('/cancel/{id}', name: 'admin_calendar_cancel', methods: ['POST'])]
    public function cancel(MaintenanceRequest $requestEntity, EntityManagerInterface $em): Response
    {
        $requestEntity->setStatus(MaintenanceRequest::STATUS_CANCELLED);
        $em->flush();

        return $this->json(['ok' => true]);
    }
}
