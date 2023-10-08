<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BookingsController;
use App\Http\Controllers\Api\ClassesController;

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

Route::get('/classes', [ClassesController::class, 'getAllClasses']);
Route::post('/classes', [ClassesController::class, 'storeClass']);
Route::delete('/classes', [ClassesController::class, 'deleteClassesByDateRange']);


Route::get('/bookings', [BookingsController::class, 'getAllBookings']);
Route::post('/bookings', [BookingsController::class, 'storeBooking']);
Route::delete('/bookings', [BookingsController::class, 'deleteBooking']);
