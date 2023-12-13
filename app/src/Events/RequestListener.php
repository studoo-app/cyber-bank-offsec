<?php

namespace App\Events;

use App\Service\CaptureTheFlagService;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;


#[AsEventListener(event: ControllerEvent::class, method: 'onRequest')]
readonly class RequestListener
{

    public function __construct(
        private CaptureTheFlagService $service,
        private Security $security,
        private UrlGeneratorInterface $urlGenerator
    )
    {
    }

    public function onRequest(ControllerEvent $event): void
    {

        if(
            in_array($event->getRequest()->getPathInfo(),["/api/extract","/admin/transfer"]) &&
            !$this->security->getUser()
        ){


            if($this->service->isIntrusionDetected()){
                $response = new RedirectResponse($this->urlGenerator->generate("app_intrusion_detected"));
                $response->send();
            }

            $this->service->logArray([
                "type"=>"ACCESS",
                "date"=>(new \DateTimeImmutable('now',new \DateTimeZone('Europe/Paris')))->format('d/m/Y H:i:s'),
                "path"=>$event->getRequest()->getPathInfo()
            ]);
        }

    }

}