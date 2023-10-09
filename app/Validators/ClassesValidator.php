<?php

declare(strict_types=1);

namespace App\Validators;

use App\Exceptions\ClassNotFoundException;
use App\Exceptions\DuplicateClassesException;
use App\Helpers\ClassesHelper;
use App\Http\Repositories\ClassesRepository;
use App\Models\Classes;

class ClassesValidator
{

    private ClassesRepository $classesRepository;

    public function __construct(ClassesRepository $classesRepository)
    {
        $this->classesRepository = $classesRepository;
    }

    public function validateClassExistsFromDateInterval(ClassesHelper $data): void
    {
        if ($this->classesRepository->existsClassesFromDateInterval($data)) {
            throw new DuplicateClassesException();
        }
    }

    public function validateClassExistsForDelete(ClassesHelper $data): void
    {
        if (!$this->classesRepository->existsClassesFromDateInterval($data)) {
            throw new ClassNotFoundException();
        }
    }
}
