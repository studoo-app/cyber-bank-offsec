<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute()]
class IsTransferSuspicious extends Constraint
{
    /*
     * Any public properties become valid options for the annotation.
     * Then, use these in your validator class.
     */
    public $message = 'Unauthorized transfer request detected !';

    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }
}
