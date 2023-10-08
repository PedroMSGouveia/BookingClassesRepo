<?php

declare(strict_types=1);

namespace App\Http\Services;

use App\Exceptions\ClassNotFoundException;
use App\Helpers\ClassesHelper;
use App\Http\Repositories\ClassesRepository;
use App\Models\Classes;
use Carbon\Carbon;

class ClassesService
{

    private $classesRepository;

    public function __construct(ClassesRepository $classesRepository)
    {
        $this->classesRepository = $classesRepository;
    }

    public function getAllClasses(ClassesHelper $params): mixed
    {
        return $this->classesRepository->getClasses($params);
    }

    public function addClasses(ClassesHelper $data): Classes|array
    {
        $startDate = Carbon::parse($data->startDate);
        $endDate = Carbon::parse($data->endDate);

        $numberOfDays = $endDate->diffInDays($startDate);

        $createdClasses = [];
        for ($i = 0; $i <= $numberOfDays; $i++) {
            $classDate = $startDate->copy()->addDays($i);
            $classDate = $classDate->format('Y-m-d');
            $class = $this->classesRepository->addClass($data->name, $classDate, $data->capacity);

            $createdClasses[] = $class;
        }

        return $createdClasses;
    }

    public function deleteClass(ClassesHelper $data): int
    {
        $deletedRows = $this->classesRepository->deleteClasses($data);

        return $deletedRows;
    }

    public function getClassIdByDate(string $date): int
    {
        $classId = $this->classesRepository->getClassIdByDate($date);

        if(is_null($classId)){
            throw new ClassNotFoundException();
        }

        return $classId;
    }
}
