<?php
namespace App\Controller;

use App\Entity\MaintenanceRequest;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class MaintenanceController extends AbstractController
{
    #[Route('/maintenance/new', name: 'maintenance_new', methods: ['POST'])]
    public function new(Request $req, EntityManagerInterface $em, SluggerInterface $slugger)
    {
        // 1) Récup champs
        $fullName     = trim((string)$req->request->get('fullName'));
        $email        = trim((string)$req->request->get('email'));
        $phone        = trim((string)$req->request->get('phone'));
        $type         = trim((string)$req->request->get('type', 'cleaning'));
        $comment      = trim((string)$req->request->get('comment'));
        $addressLine1 = trim((string)$req->request->get('addressLine1'));
        $addressLine2 = trim((string)$req->request->get('addressLine2'));
        $city         = trim((string)$req->request->get('city'));
        $postalCode   = trim((string)$req->request->get('postalCode'));
        $dateIso      = (string)$req->request->get('date'); // 'YYYY-MM-DDTHH:mm:ss'

        try {
            $requestedDate = new \DateTimeImmutable($dateIso ?: 'now');
        } catch (\Throwable) {
            $requestedDate = new \DateTimeImmutable('now');
        }

        // 2) Upload des photos
        $publicDir = $this->getParameter('kernel.project_dir').'/public';
        $uploadDir = $publicDir.'/uploads/maintenance';
        if (!is_dir($uploadDir)) { @mkdir($uploadDir, 0775, true); }

        $uploadedUrls = [];
        /** @var \Symfony\Component\HttpFoundation\File\UploadedFile[] $files */
        $files = $req->files->get('photos', []);
        $maxBytes = 10 * 1024 * 1024; // 10 Mo
        $allowedMime = ['image/jpeg','image/png','image/webp'];

        foreach ($files as $file) {
            if (!$file) { continue; }
            if (!in_array($file->getMimeType(), $allowedMime, true)) { continue; }
            if ($file->getSize() > $maxBytes) { continue; }

            $base     = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $safeBase = $slugger->slug($base)->lower();
            $ext      = $file->guessExtension() ?: 'bin';
            $name     = $safeBase.'-'.uniqid().'.'.$ext;

            $file->move($uploadDir, $name);
            $uploadedUrls[] = '/uploads/maintenance/'.$name; // URL publique
        }

        // 3) Persistance
        $m = new MaintenanceRequest();
        $m->setFullName($fullName)
          ->setEmail($email)
          ->setPhone($phone)
          ->setType($type)
          ->setComment($comment)
          ->setAddressLine1($addressLine1)
          ->setAddressLine2($addressLine2 ?: null)
          ->setCity($city)
          ->setPostalCode($postalCode)
          ->setRequestedDate($requestedDate)
          ->setPhotos($uploadedUrls)
          ->setStatus('pending');

        $em->persist($m);
        $em->flush();

        $this->addFlash('success', sprintf(
            'Demande enregistrée pour le %s. %d photo(s) reçue(s).',
            $requestedDate->format('d/m/Y'),
            count($uploadedUrls)
        ));

        return $this->redirectToRoute('app_home');
    }
}
