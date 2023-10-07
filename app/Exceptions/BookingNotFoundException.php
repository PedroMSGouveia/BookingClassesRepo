<?php

namespace App\Exceptions;

use Exception;

class BookingNotFoundException extends Exception
{
    public function __construct()
    {
        $this->message = 'Booking not found';
    }
}
