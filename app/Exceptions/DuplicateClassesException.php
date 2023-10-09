<?php

namespace App\Exceptions;

use Exception;

class DuplicateClassesException extends Exception
{
    public function __construct()
    {
        $this->message = 'Class already exists at the specified date';
    }
}
