<?php

namespace App\Http\Repositories;

use App\Helpers\ExistsBookingParams;
use App\Helpers\ValidateBookingExistsParams;
use App\Models\Bookings;

class BookingsRepository
{
    /**
     * getClasses
     * Retrieves all classes from database with the bookings of each one
     * @param  int $page when set returns the data with pagination, the specific page
     * @return void
     */
    public function getBookings(string $startDate = null, string $endDate = null, int $page = null)
    {
        $query = Bookings::query();

        $bookings = null;

        if (!is_null($startDate)) {
            $query->whereHas('class', function ($query) use ($startDate) {
                $query->where('date', '>=', $startDate);
            });
        }

        if (!is_null($endDate)) {
            $query->whereHas('class', function ($query) use ($endDate) {
                $query->where('date', '<=', $endDate);
            });
        }

        if (!is_null($page)) {
            $bookings = $query->with('class')->paginate(config('constants.pagination.page_size'));
        } else {
            $bookings = $query->with('class')->get();
        }

        return $bookings;
    }

    public function addBooking(string $personName, int $classId)
    {
        return Bookings::create([
            'person_name' => $personName,
            'classes_id' => $classId
        ]);
    }

    public function deleteBookings(string $personName, int $classId)
    {
        return Bookings::where('classes_id', '=', $classId)
        ->where('person_name', '=', $personName)
        ->delete();
    }

    public function existsBooking(ExistsBookingParams $data): bool
    {
        return Bookings::where('person_name', $data->getPersonName())
        ->where('date', $data->getDate())
        ->exists();
    }
}
