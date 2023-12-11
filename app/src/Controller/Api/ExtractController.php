<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ExtractController extends AbstractController
{
    #[Route('/api/extract', name: 'app_api_extract')]
    public function index(): Response
    {
        return $this->render('api/extract/index.html.twig', [
            'controller_name' => 'ExtractController',
        ]);
    }
}
