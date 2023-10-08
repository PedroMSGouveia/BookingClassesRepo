<?php

declare(strict_types=1);

namespace App\Http\Services;

use App\Exceptions\BookingNotFoundException;
use App\Helpers\BookingsHelper;
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

    public function getAllBookings(BookingsHelper $params): mixed
    {
        return $this->bookingsRepository->getBookings($params);
    }

    public function storeBooking(BookingsHelper $params): Bookings
    {
        $classId = $this->classesService->getClassIdByDate($params->date);
        return $this->bookingsRepository->addBooking($params->personName, $classId);
    }

    public function deleteBookings(BookingsHelper $params): int
    {
        $params->classesId = $this->classesService->getClassIdByDate($params->date);
        $deletedRows = $this->bookingsRepository->deleteBookings($params);

        return $deletedRows;
    }
}
