<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BookingsApiController;
use App\Http\Controllers\Api\ClassesApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('/classes', [ClassesApiController::class, 'getAllClasses']);
Route::post('/classes', [ClassesApiController::class, 'storeClass']);
Route::delete('/classes', [ClassesApiController::class, 'deleteClassesByDateRange']);


Route::get('/bookings', [BookingsApiController::class, 'getAllBookings']);
Route::post('/bookings', [BookingsApiController::class, 'storeBooking']);
Route::delete('/bookings', [BookingsApiController::class, 'deleteBooking']);
