<?php

declare(strict_types=1);

namespace App\Validators;

use App\Exceptions\BookingNotFoundException;
use App\Exceptions\ClassNotFoundException;
use App\Exceptions\DuplicateBookingsException;
use App\Helpers\BookingsHelper;
use App\Http\Repositories\BookingsRepository;
use App\Http\Repositories\ClassesRepository;

class BookingsValidator
{

    private BookingsRepository $bookingsRepository;
    private ClassesRepository $classesRepository;

    public function __construct(BookingsRepository $bookingsRepository, ClassesRepository $classesRepository)
    {
        $this->bookingsRepository = $bookingsRepository;
        $this->classesRepository = $classesRepository;
    }

    public function validateBookingExistsForDelete(BookingsHelper $params): void
    {
        $class = $this->classesRepository->getClassIdByDate($params->date);

        if(is_null($class)){
            throw new ClassNotFoundException();
        }

        $params->classesId = (int) $class;
        if (!$this->bookingsRepository->existsBookingFromPersonName($params)) {
            throw new BookingNotFoundException();
        }
    }

    public function checkBookingExists(BookingsHelper $params): void
    {
        $class = $this->classesRepository->getClassIdByDate($params->date);

        if(is_null($class)){
            throw new ClassNotFoundException();
        }

        $params->classesId = (int) $class;
        if ($this->bookingsRepository->existsBookingFromPersonName($params)) {
            throw new DuplicateBookingsException();
        }
    }
}
