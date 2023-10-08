<?php

declare(strict_types=1);

namespace App\Http\Repositories;

use App\Helpers\BookingsHelper;
use App\Helpers\ValidateBookingExistsParams;
use App\Models\Bookings;
use stdClass;

class BookingsRepository
{
    /**
     * getClasses
     * Retrieves all classes from database with the bookings of each one
     * @param  int $page when set returns the data with pagination, the specific page
     * @return void
     */
    public function getBookings(BookingsHelper $params): mixed
    {
        $query = Bookings::query();

        if (isset($params->startDate)) {
            $startDate = $params->startDate;
            $query->whereHas('class', function ($query) use ($startDate) {
                $query->where('date', '>=', $startDate);
            });
        }

        if (isset($params->endDate)) {
            $endDate = $params->endDate;
            $query->whereHas('class', function ($query) use ($endDate) {
                $query->where('date', '<=', $endDate);
            });
        }

        if (isset($params->page)) {
            $results = $query->with('class')->paginate(config('constants.pagination.page_size'));
            $responseData = [
                'total' => $results->total(),
                'count' => count($results->items()),
                'data' => $results->items(),
            ];
        } else {
            $results = $query->with('class')->get();
            $responseData = [
                'data' => $results,
            ];
        }

        return $responseData;
    }

    public function addBooking(string $personName, int $classId): Bookings
    {
        return Bookings::create([
            'person_name' => $personName,
            'classes_id' => $classId
        ]);
    }

    public function deleteBookings(BookingsHelper $params): int
    {
        return Bookings::where('classes_id', '=', $params->classesId)
        ->where('person_name', '=', $params->personName)
        ->delete();
    }

    public function existsBookingFromPersonName(BookingsHelper $params): bool
    {
        return Bookings::where('person_name', $params->personName)
        ->where('classes_id', $params->classesId)
        ->exists();
    }
}
