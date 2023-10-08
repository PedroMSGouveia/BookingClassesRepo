<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Validation\ValidationException;
use Illuminate\Contracts\Validation\Validator;

class CustomValidationException extends ValidationException //Change class name (CustomValidationException) para (Change)
{
    public $errors;

    public function __construct($validator)
    {
        parent::__construct($validator);

        $this->message = 'Validation error';

        $customErrors = [];
        foreach ($validator->errors()->messages() as $field => $errors) {
            foreach ($errors as $error) {
                $customErrors[] = "$error";
            }
        }

        $this->errors = $customErrors;
    }
}
