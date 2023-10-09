<?php

declare(strict_types=1);

namespace Tests\Feature\Validators;

use App\Exceptions\ClassNotFoundException;
use App\Exceptions\DuplicateClassesException;
use App\Helpers\ClassesHelper;
use App\Http\Repositories\ClassesRepository;
use App\Models\Classes;
use App\Validators\ClassesValidator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ClassesValidatorTest extends TestCase
{
    use RefreshDatabase;

    public function testValidateClassExistsFromDateInterval(): void
    {
        $repository = $this->createMock(ClassesRepository::class);
        $validator = new ClassesValidator($repository);

        $data = ClassesHelper::withStdClass((object)['startDate' => '2023-12-03', 'endDate' => '2023-12-04']);

        $repository->expects($this->once())
            ->method('existsClassesFromDateInterval')
            ->with($data)
            ->willReturn(true);

        $this->expectException(DuplicateClassesException::class);
        $validator->validateClassExistsFromDateInterval($data);
    }

    public function testValidateClassExistsForDelete(): void
    {
        $repository = $this->createMock(ClassesRepository::class);
        $validator = new ClassesValidator($repository);

        $data = ClassesHelper::withStdClass((object)['startDate' => '2023-12-03', 'endDate' => '2023-12-04']);

        $repository->expects($this->once())
            ->method('existsClassesFromDateInterval')
            ->with($data)
            ->willReturn(false);

        $this->expectException(ClassNotFoundException::class);
        $validator->validateClassExistsForDelete($data);
    }
}
