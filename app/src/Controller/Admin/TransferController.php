<?php

namespace App\Controller\Admin;

use App\Entity\Account;
use App\Entity\Operation;
use App\Entity\Transfer;
use App\Form\TransferType;
use App\Service\CaptureTheFlagService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TransferController extends AbstractController
{

    public function __construct(
        private readonly CaptureTheFlagService $service
    )
    {
    }

    #[Route('/admin/transfer', name: 'app_admin_transfer', methods: ['GET', 'POST'])]
    public function transfer(Request $request,EntityManagerInterface $manager): Response
    {

        $transfer = new Transfer();
        $form = $this->createForm(TransferType::class, $transfer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $srcAccount = $manager->getRepository(Account::class)
                ->findOneBy(["number"=>$transfer->getSrcAccountNumber()]);
            $destAccount = $manager->getRepository(Account::class)
                ->findOneBy(["number"=>$transfer->getDestAccountNumber()]);

            $srcAccount->addOperation($this->buildOperation($srcAccount,$transfer->getAmount(),true));
            $destAccount->addOperation($this->buildOperation($destAccount,$transfer->getAmount()));

            $manager->persist($srcAccount);
            $manager->persist($destAccount);

            $manager->flush();

            $this->addFlash('success',"Virement effectué avec succès");
            return $this->redirectToRoute('app_admin_transfer', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/transfer/index.html.twig', [
            'transfer' => $transfer,
            'form' => $form,
            'flags'=>$this->service->getFlags()
        ]);
    }

    private function buildOperation(Account $account, int $amount,bool $isSrc=false): Operation
    {
        $operation = new Operation();
        $operation->setAccount($account);
        $operation->setLabel("Transfert Manuel");

        $operation->setAmount($isSrc ? intval("-$amount") : $amount);

        return $operation;
    }
}
