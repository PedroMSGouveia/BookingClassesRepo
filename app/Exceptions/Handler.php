<?php

namespace App\Exceptions;

use Carbon\Exceptions\InvalidFormatException;
use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
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
                return response()->json(['message' =>'Something went wrong, please try again later'], 500);
            }
        });
    }
}
