<?php

namespace App\Http\Repositories;

use App\Models\Classes;

class ClassesRepository
{
    /**
     * getClasses
     * Retrieves all classes from database with the bookings of each one
     * @param  int $page when set returns the data with pagination, the specific page
     * @return void
     */
    public function getClasses(int $page = null)
    {
        if(is_null($page)){
            $toReturn = Classes::with('bookings')->get();
        } else {
            $toReturn = Classes::with('bookings')->paginate(config('constants.pagination.page_size'));
        }

        return $toReturn;
    }

    public function addClass(string $name, string $classDate, int $capacity)
    {
        return Classes::create([
            'name' => $name,
            'date' => $classDate,
            'capacity' => $capacity,
        ]);
    }

    public function deleteClasses(string $startDate, string $endDate)
    {
        return Classes::where('date', '>=', $startDate)
                ->where('date', '<=', $endDate)
                ->delete();
    }

    public function getClassIdByDate(string $date): int{
        return Classes::getIdByDate($date);
    }
}
