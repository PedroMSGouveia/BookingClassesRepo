<?php

declare(strict_types=1);

namespace App\Http\Requests\Classes;

use App\Exceptions\CustomValidationException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class GetClassesRequest extends ClassRequest
{
    public function rules(): array
    {
        return [
            'page' => 'integer',
            'name' => 'string',
        ];
    }
}
