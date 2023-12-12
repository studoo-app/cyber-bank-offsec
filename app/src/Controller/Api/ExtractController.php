<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\CaptureTheFlagService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ExtractController extends AbstractController
{
    #[Route('/api/extract', name: 'app_api_extract')]
    public function extract(UserRepository $repository): JsonResponse
    {
        $clients = $repository->findClients();
        return new JsonResponse(["data"=>array_map(function(User $client){
            return [
                "name"=>$client->getName(),
                "email"=>$client->getEmail(),
                "account"=>$client->getAccount()->getNumber(),
                "balance"=>$client->getAccount()->getBalance()
            ];
        },$clients)]);
    }
}
