<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class AccountNumberIsValid extends Constraint
{
    /*
     * Any public properties become valid options for the annotation.
     * Then, use these in your validator class.
     */
    public $message = 'Account identified by number "{{ value }}" not found.';
}
