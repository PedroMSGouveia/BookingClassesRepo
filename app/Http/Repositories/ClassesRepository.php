<?php

namespace App\Http\Repositories;

use App\Exceptions\ClassNotFoundException;
use App\Helpers\ClassesHelper;
use App\Helpers\ExistsClassDateInterval;
use App\Models\Classes;
use Symfony\Component\ErrorHandler\Error\ClassNotFoundError;

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

    public function deleteClasses(ClassesHelper $data)
    {
        return Classes::where('date', '>=', $data->startDate)
                ->where('date', '<=', $data->endDate)
                ->delete();
    }

    public function getClassIdByDate(string $date)
    {
        $classId = Classes::getIdByDate($date);

        if(is_null($classId)) throw new ClassNotFoundException();

        return $classId;
    }

    public function existsClassesFromDate(string $date)
    {
        return Classes::where('date', $date)
        ->exists();
    }

    public function existsClassesFromDateInterval(ClassesHelper $data)
    {
        return Classes::where('date', '>=', $data->startDate)
        ->where('date', '<=', $data->endDate)
        ->exists();
    }
}
