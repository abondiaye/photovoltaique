<?php
declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class OnePageController extends AbstractController
{
    #[Route('/', name: 'onepage', methods: ['GET'])]
    public function __invoke(): Response
    {
        return $this->render('onepage.html.twig');
    }
}
