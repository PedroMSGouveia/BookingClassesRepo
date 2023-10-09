<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Helpers\BookingsHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Bookings\DeleteBookingsRequest;
use App\Http\Requests\Bookings\GetBookingsRequest;
use App\Http\Requests\Bookings\StoreBookingsRequest;
use App\Http\Services\BookingsService;
use App\Validators\BookingsValidator;
use Illuminate\Http\JsonResponse;

class BookingsController extends Controller
{

    private BookingsService $bookingsService;
    private BookingsValidator $bookingsValidator;

    public function __construct(BookingsService $bookingsService, BookingsValidator $bookingsValidator)
    {
        $this->bookingsService = $bookingsService;
        $this->bookingsValidator = $bookingsValidator;
    }

    /**
     * @OA\Get(
     *     tags={"Bookings"},
     *     path="/api/bookings",
     *     summary="List bookings",
     *     operationId="list_bookings",
     *     description="Returns a list of bookings that are registered on a server",
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Page number for paginated results",
     *         required=false,
     *         @OA\Schema(type="integer", default=1)
     *     ),
     *     @OA\Parameter(
     *         name="className",
     *         in="query",
     *         description="Class name",
     *         required=false,
     *         @OA\Schema(type="string", example="Cycling")
     *     ),
     *     @OA\Parameter(
     *         name="personName",
     *         in="query",
     *         description="Booked person name",
     *         required=false,
     *         @OA\Schema(type="string", example="Pedro Gouveia")
     *     ),
     *     @OA\Parameter(
     *         name="startDate",
     *         in="query",
     *         description="Start date to filter between dates",
     *         required=false,
     *         @OA\Schema(type="string", format="date", example="2023-12-01")
     *     ),
     *     @OA\Parameter(
     *         name="endDate",
     *         in="query",
     *         description="End date to filter between dates",
     *         required=false,
     *         @OA\Schema(type="string", format="date", example="2023-12-15")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of bookings",
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Something went wrong",
     *     )
     * )
     */
    public function getAllBookings(GetBookingsRequest $request): JsonResponse
    {
        $params = BookingsHelper::withStdClass((object) $request->validated());

        return response()->json($this->bookingsService->getAllBookings($params));
    }

    /**
     * @OA\Post(
     *     tags={"Bookings"},
     *     path="/api/bookings",
     *     summary="Create a new booking",
     *     operationId="create_booking",
     *     description="Adds a new booking with the provided information.",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Booking information",
     *         @OA\JsonContent(
     *             required={"personName", "date"},
     *             @OA\Property(property="personName", type="string", example="Pedro Gouveia", description="The name of the person making the booking"),
     *             @OA\Property(property="date", type="string", format="date", example="2023-12-01", description="Date of the booking"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Booking created successfully",
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request, invalid input",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Class not found for the specified date",
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Something went wrong",
     *     )
     * )
     */
    public function storeBooking(StoreBookingsRequest $request): JsonResponse
    {
        $params = BookingsHelper::withStdClass((object) $request->validated());

        $this->bookingsValidator->checkBookingExists($params);

        return response()->json($this->bookingsService->storeBooking($params), 201);
    }

    /**
     * @OA\Delete(
     *     tags={"Bookings"},
     *     path="/api/bookings",
     *     summary="Delete a booking with given name and date",
     *     operationId="delete_booking_by_personName_and_date",
     *     description="Deletes all bookings with the same person name and date.",
     *      @OA\RequestBody(
     *         required=true,
     *         description="Booking information",
     *         @OA\JsonContent(
     *             required={"personName", "date"},
     *             @OA\Property(property="personName", type="string", example="Pedro Gouveia", description="The name of the person who booked the class"),
     *             @OA\Property(property="date", type="string", format="date", example="2023-12-15", description="The date of the booking"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Booking deleted successfully",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Booking not found within the specified date and name",
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request, invalid input",
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Something went wrong",
     *     )
     * )
     */
    public function deleteBooking(DeleteBookingsRequest $request): JsonResponse
    {
        $params = BookingsHelper::withStdClass((object) $request->validated());

        $this->bookingsValidator->validateBookingExistsForDelete($params);

        $this->bookingsService->deleteBookings($params);

        return response()->json(['message' => 'Booking deleted successfully'], 200);
    }

}
