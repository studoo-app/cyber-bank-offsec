<?php

namespace App\Events;

use App\Entity\Operation;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\PostPersistEventArgs;
use Doctrine\ORM\Events;

#[AsEntityListener(event: Events::postPersist, method: 'postPersist', entity: Operation::class)]
readonly class OperationListener
{

    public function __construct(
        private EntityManagerInterface $manager
    )
    {
    }

    public function postPersist(Operation $operation, PostPersistEventArgs $args):void
    {

        $operation->getAccount()->calculateBalance();
        $this->manager->persist($operation->getAccount());
        $this->manager->flush();
    }
}