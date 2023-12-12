<?php

namespace App\Controller;

use App\Service\CaptureTheFlagService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountController extends AbstractController
{
    public function __construct(
        private readonly CaptureTheFlagService $service
    )
    {
    }

    #[Route('/account', name: 'app_account')]
    public function index(): Response
    {
        return $this->render('account/index.html.twig', [
            'account' => $this->getUser()->getAccount(),
            'flags'=>$this->service->getFlags()
        ]);
    }
}
