<?php

namespace App\Exceptions;

use Exception;

class ClassNotFoundException extends Exception
{
    public function __construct()
    {
        $this->message = 'Class not found';
    }
}
