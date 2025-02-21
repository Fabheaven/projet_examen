<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class Capitalized extends Constraint
{
    public $message = 'Le champ "{{ string }}" doit commencer par une majuscule.';
}
