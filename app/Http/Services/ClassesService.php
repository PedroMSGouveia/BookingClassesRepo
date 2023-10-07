<?php

namespace App\Http\Services;

use App\Exceptions\ClassNotFoundException;
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

    /**
     * getAllClasses
     * The main goal of this function is to get all classes from database
     * It includes all bookings inside the class
     * @param  int $page when set returns the data with pagination, the specific page
     * @return void
     */
    public function getAllClasses(int $page = null)
    {
        return $this->classesRepository->getClasses($page);
    }

    /**
     * addClasses
     * By receiving a start date and end date it inserts as many classes as there are days between those two dates
     * @param  mixed $data has all validated fields from request body
     * @return Classes[] returns a array of the inserted classes
     */
    public function addClasses($data)
    {
        $name = $data['name'];
        $startDate = Carbon::parse($data['startDate']);
        $endDate = Carbon::parse($data['endDate']);
        $capacity = $data['capacity'];

        $numberOfDays = $endDate->diffInDays($startDate);

        $createdClasses = [];
        for ($i = 0; $i <= $numberOfDays; $i++) {
            $classDate = $startDate->copy()->addDays($i);

            $class = $this->classesRepository->addClass($name, $classDate, $capacity);

            $createdClasses[] = $class;
        }

        return $createdClasses;
    }

    public function deleteClass(Carbon $startDate, Carbon $endDate)
    {
        $deletedRows = $this->classesRepository->deleteClasses($startDate, $endDate);

        if(is_null($deletedRows) || $deletedRows === 0){
            throw new ClassNotFoundException();
        }

        return $deletedRows;
    }

    public function getClassIdByDate(string $date):int{
        $classId = $this->classesRepository->getClassIdByDate($date);

        if(is_null($classId)){
            throw new ClassNotFoundException();
        }

        return $classId;
    }
}
