<?php

namespace App\Events;

use App\Entity\Operation;
use App\Service\CaptureTheFlagService;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\PostPersistEventArgs;
use Doctrine\ORM\Events;
use Symfony\Bundle\SecurityBundle\Security;

#[AsEntityListener(event: Events::postPersist, method: 'postPersist', entity: Operation::class)]
readonly class OperationListener
{

    public function __construct(
        private EntityManagerInterface $manager,
        private CaptureTheFlagService $service,
        private Security $security
    )
    {
    }

    public function postPersist(Operation $operation, PostPersistEventArgs $args):void
    {

        $operation->getAccount()->calculateBalance();
        $this->manager->persist($operation->getAccount());
        $this->manager->flush();

        if(
            !$this->security->getUser() &&
            $operation->getAccount() === $this->service->getShadowAccount()
        ){
            $this->service->logArray([
                "type"=>"OPERATION",
                "date"=>(new \DateTimeImmutable('now',new \DateTimeZone('Europe/Paris')))->format('d/m/Y H:i:s'),
                "accountNumber" => $operation->getAccount()->getNumber(),
                "balance"=>$operation->getAccount()->getBalance(),
                "amount"=>$operation->getAmount(),
            ]);
        }

    }

}