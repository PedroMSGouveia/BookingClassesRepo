<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Bookings\DeleteBookingsRequest;
use App\Http\Requests\Bookings\GetBookingsRequest;
use App\Http\Requests\Bookings\StoreBookingsRequest;
use App\Http\Services\BookingsService;
use App\Http\Services\UtilsService;
use Illuminate\Http\Request;

class BookingsApiController extends Controller
{

    private BookingsService $bookingsService;
    private UtilsService $utilsService;

    public function __construct(UtilsService $utilsService, BookingsService $bookingsService)
    {
        $this->bookingsService = $bookingsService;
        $this->utilsService = $utilsService;
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
    public function getAllBookings(GetBookingsRequest $request)
    {
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');
        $page = $request->input('page');

        return response()->json($this->bookingsService->getAllBookings($startDate, $endDate, $page));
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
     *         response=500,
     *         description="Something went wrong",
     *     )
     * )
     */
    public function storeBooking(StoreBookingsRequest $request)
    {
        $validatedData = $request->validated();

        $personName = $validatedData['personName'];
        $date = $this->utilsService->parseDate($validatedData['date']);

        return response()->json($this->bookingsService->storeBooking($personName, $date), 201);
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
    public function deleteBooking(DeleteBookingsRequest $request)
    {
        $validatedData = $request->validated();

        $personName = $validatedData['personName'];
        $date = $this->utilsService->parseDate($validatedData['date']);

        $this->bookingsService->deleteBookings($personName, $date);

        return response()->json(['message' => 'Booking deleted successfully'], 200);
    }

}
