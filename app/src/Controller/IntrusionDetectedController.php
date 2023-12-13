<?php

namespace App\Controller;

use App\Service\CaptureTheFlagService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IntrusionDetectedController extends AbstractController
{
    public function __construct(
        private readonly CaptureTheFlagService $service
    )
    {
    }

    #[Route('/intrusion/detected', name: 'app_intrusion_detected')]
    public function index(): Response
    {
        return $this->render('ctf/intrusion_detected.twig', [
            'controller_name' => 'IntrusionDetectedController',
            'flags'=>$this->service->getFlags()
        ]);
    }
}
