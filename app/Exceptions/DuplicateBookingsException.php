<?php

namespace App\Exceptions;

use Exception;

class DuplicateBookingsException extends Exception
{
    public function __construct()
    {
        $this->message = 'Booking already exists at the specified date';
    }
}
