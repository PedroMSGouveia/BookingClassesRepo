<?php

namespace App\Http\Services;

use App\Exceptions\BookingNotFoundException;
use App\Http\Repositories\BookingsRepository;
use App\Models\Bookings;
use App\Models\Classes;
use Carbon\Carbon;

class BookingsService
{

    private $bookingsRepository;
    private $classesService;

    public function __construct(BookingsRepository $bookingsRepository, ClassesService $classesService)
    {
        $this->bookingsRepository = $bookingsRepository;
        $this->classesService = $classesService;
    }

    /**
     * getAllClasses
     * The main goal of this function is to get all classes from database
     * It includes all bookings inside the class
     * @param  int $page when set returns the data with pagination, the specific page
     * @return void
     */
    public function getAllBookings(string $startDate = null, string $endDate = null, int $page = null): mixed
    {
        return $this->bookingsRepository->getBookings($startDate, $endDate, $page);
    }

    public function storeBooking(string $personName, Carbon $date): Bookings
    {
        $classId = $this->classesService->getClassIdByDate($date);
        return $this->bookingsRepository->addBooking($personName, $classId);
    }

    public function deleteBookings(string $personName, Carbon $date): int
    {
        $deletedRows = $this->bookingsRepository->deleteBookings($personName, $date);

        if(is_null($deletedRows) || $deletedRows === 0){
            throw new BookingNotFoundException();
        }

        return $deletedRows;
    }
}
