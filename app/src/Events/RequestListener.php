<?php

namespace App\Events;

use App\Service\CaptureTheFlagService;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Event\ControllerEvent;

#[AsEventListener(event: ControllerEvent::class, method: 'onRequest')]
readonly class RequestListener
{

    public function __construct(
        private CaptureTheFlagService $service
    )
    {
    }

    public function onRequest(ControllerEvent $event):void
    {

        if( in_array($event->getRequest()->getPathInfo(),["/api/extract","/admin/transfer"])){
            $this->service->logArray([
                "type"=>"ACCESS",
                "date"=>(new \DateTimeImmutable('now',new \DateTimeZone('Europe/Paris')))->format('d/m/Y H:i:s'),
                "path"=>$event->getRequest()->getPathInfo()
            ]);
        }

    }

}