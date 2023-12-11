<?php

namespace App\Validator;

use App\Repository\AccountRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class AccountNumberIsValidValidator extends ConstraintValidator
{


    public function __construct(
       private readonly AccountRepository $repository
    )
    {
    }

    public function validate($value, Constraint $constraint): void
    {
        /* @var AccountNumberIsValid $constraint */

        if (null === $value || '' === $value) {
            return;
        }

        if(!$this->repository->findOneBy(["number"=>$value])){
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value)
                ->addViolation();
        }

    }
}
