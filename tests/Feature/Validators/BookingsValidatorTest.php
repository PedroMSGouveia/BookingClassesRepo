<?php

declare(strict_types=1);

namespace Tests\Feature\Validators;

use App\Exceptions\BookingNotFoundException;
use App\Exceptions\DuplicateBookingsException;
use App\Helpers\BookingsHelper;
use App\Http\Repositories\BookingsRepository;
use App\Http\Repositories\ClassesRepository;
use App\Validators\BookingsValidator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingsValidatorTest extends TestCase
{
    use RefreshDatabase;

    public function tearDown(): void
    {
        parent::tearDown();
    }

    public function testValidateBookingExistsForDelete(): void
    {
        $bookingsRepository = $this->createMock(BookingsRepository::class);
        $classesRepository = $this->createMock(ClassesRepository::class);
        $validator = new BookingsValidator($bookingsRepository, $classesRepository);

        $params = BookingsHelper::withStdClass((object)['date' => '2023-12-01', 'personName' => 'Pedro Gouveia']);

        $classesRepository->expects($this->once())
            ->method('getClassIdByDate')
            ->with('2023-12-01')
            ->willReturn(1);

        $bookingsRepository->expects($this->once())
            ->method('existsBookingFromPersonName')
            ->with($params)
            ->willReturn(false);

        $this->expectException(BookingNotFoundException::class);
        $validator->validateBookingExistsForDelete($params);
    }

    public function testCheckBookingExists(): void
    {
        $bookingsRepository = $this->createMock(BookingsRepository::class);
        $classesRepository = $this->createMock(ClassesRepository::class);
        $validator = new BookingsValidator($bookingsRepository, $classesRepository);

        $params = new BookingsHelper();
        $params->date = '2023-12-01';
        $params->personName = 'Pedro Gouveia';

        $classesRepository->expects($this->once())
            ->method('getClassIdByDate')
            ->with('2023-12-01')
            ->willReturn(1);

        $bookingsRepository->expects($this->once())
            ->method('existsBookingFromPersonName')
            ->with($params)
            ->willReturn(true);

        $this->expectException(DuplicateBookingsException::class);
        $validator->checkBookingExists($params);
    }
}
