<?php

namespace App\Validator;

use App\Service\CaptureTheFlagService;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class IsTransferSuspiciousValidator extends ConstraintValidator
{


    public function __construct(
        private readonly Security $security,
        private readonly CaptureTheFlagService $service
    )
    {
    }

    public function validate($value, Constraint $constraint)
    {
        /* @var IsTransferSuspicious $constraint */

        if (null === $value || '' === $value) {
            return;
        }

        if(!$this->security->getUser() && $value->getAmount() >= 500){

            $this->context->buildViolation($constraint->message)->addViolation();
            $this->service->logArray([
                "type"=>"ALERT",
                "date"=>(new \DateTimeImmutable('now',new \DateTimeZone('Europe/Paris')))->format('d/m/Y H:i:s'),
                "code"=>"SUSPICIOUS TRANSFER",
                "srcAccount"=>$value->getSrcAccountNumber(),
                "destAccount"=>$value->getDestAccountNumber(),
                "amount"=>$value->getAmount(),
            ]);
        }


    }
}
