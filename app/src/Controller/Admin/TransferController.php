<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TransferController extends AbstractController
{
    #[Route('/admin/transfer', name: 'app_admin_transfer')]
    public function index(): Response
    {
        return $this->render('admin/transfer/index.html.twig', [
            'controller_name' => 'TransferController',
        ]);
    }
}
