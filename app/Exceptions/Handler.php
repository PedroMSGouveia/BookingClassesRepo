<?php

namespace App\Exceptions;

use Carbon\Exceptions\InvalidFormatException;
use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Throwable;

class Handler extends ExceptionHandler
{
    public function register(): void
    {
        $this->renderable(function (CustomValidationException $cve, Request $request) {
            if ($request->is('api/*')) {
                return response()->json(['message' => 'Validation error', 'errors' => $cve->errors], 400);
            }
        });

        $this->renderable(function (ClassNotFoundException $cnfe, Request $request) {
            if ($request->is('api/*')) {
                return response()->json(['message' => $cnfe->getMessage()], 404);
            }
        });

        $this->renderable(function (BookingNotFoundException $bnfe, Request $request) {
            if ($request->is('api/*')) {
                return response()->json(['message' => $bnfe->getMessage()], 404);
            }
        });

        $this->renderable(function (InvalidFormatException $ive, Request $request) {
            if ($request->is('api/*')) {
                return response()->json(['message' =>'Date format error'], 400);
            }
        });

        $this->renderable(function (Exception $e, Request $request) {
            if ($request->is('api/*')) {
                //return response()->json(['message' =>$e->getMessage()], 500);
                return response()->json(['message' =>'Something went wrong, please try again later'], 500);
            }
        });
    }
}
