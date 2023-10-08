<?php

declare(strict_types=1);

namespace App\Http\Repositories;

use App\Exceptions\ClassNotFoundException;
use App\Helpers\ClassesHelper;
use App\Models\Classes;
use stdClass;
use Symfony\Component\ErrorHandler\Error\ClassNotFoundError;
use Carbon\Carbon;

class ClassesRepository
{

    public function getClasses(ClassesHelper $params): mixed
    {
        $query = Classes::query();

        if (isset($params->name)) {
            $name = $params->name;
            $query->whereRaw("UPPER(name) LIKE '%". strtoupper($name)."%'");
        }

        if (isset($params->page)) {
            $results = $query->with('bookings')->paginate(config('constants.pagination.page_size'));
            $responseData = [
                'total' => $results->total(),
                'count' => count($results->items()),
                'data' => $results->items(),
            ];
        } else {
            $results = $query->with('bookings')->get();
            $responseData = [
                'data' => $results,
            ];
        }

        return $responseData;
    }

    public function addClass(string $name, string $classDate, int $capacity): Classes
    {
        return Classes::create([
            'name' => $name,
            'date' => $classDate,
            'capacity' => $capacity,
        ]);
    }

    public function deleteClasses(ClassesHelper $data): int
    {
        return Classes::where('date', '>=', $data->startDate)
                ->where('date', '<=', $data->endDate)
                ->delete();
    }

    public function getClassIdByDate(string $date):int
    {
        $classId = Classes::getIdByDate($date);

        return $classId;
    }

    public function existsClassesFromDate(string $date) : bool
    {
        return Classes::where('date', $date)
        ->exists();
    }

    public function existsClassesFromDateInterval(ClassesHelper $data) : bool
    {
        return Classes::whereBetween('date', [$data->startDate, $data->endDate])->exists();
    }
}
