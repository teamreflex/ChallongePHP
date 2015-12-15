<?php

namespace Reflex\Challonge\Exceptions;

class UnexpectedErrorException extends \Exception
{
    public $errors;

    public function __construct($errors)
    {
        $this->errors = $errors;
    }

}
