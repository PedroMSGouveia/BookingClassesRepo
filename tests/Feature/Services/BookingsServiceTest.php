<?php

declare(strict_types=1);

namespace Tests\Feature\Services;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Helpers\BookingsHelper;
use Tests\TestCase;
use App\Http\Services\BookingsService;
use App\Http\Services\ClassesService;
use App\Http\Repositories\BookingsRepository;
use App\Http\Repositories\ClassesRepository;
use App\Models\Bookings;
use Illuminate\Pagination\LengthAwarePaginator;

class BookingsServiceTest extends TestCase
{
    public function testGetAllBookings(): void
    {
        $bookingsRepository = $this->createMock(BookingsRepository::class);

        $classesService = $this->createMock(ClassesService::class);

        $service = new BookingsService($bookingsRepository, $classesService);

        $params = BookingsHelper::withStdClass((object) ['page'=>1, 'startDate'=>'2023-12-01', 'endDate'=>'2023-12-02']);

        $paginator = $this->createMock(LengthAwarePaginator::class);

        $bookingsRepository->expects($this->once())
            ->method('getBookings')
            ->with($params)
            ->willReturn($paginator);

        $result = $service->getAllBookings($params);

        $this->assertSame($paginator, $result);
    }

    public function testStoreBooking(): void
    {
        $bookingsRepository = $this->createMock(BookingsRepository::class);

        $classesService = $this->createMock(ClassesService::class);

        $service = new BookingsService($bookingsRepository, $classesService);

        $params = BookingsHelper::withStdClass((object) ['personName'=>'Pedro Gouveia', 'date'=>'2023-12-01']);

        $classesService->expects($this->once())
            ->method('getClassIdByDate')
            ->with($params->date)
            ->willReturn(1); 

        $bookingsRepository->expects($this->once())
            ->method('addBooking')
            ->with($params->personName, 1) 
            ->willReturn(new Bookings());

        $result = $service->storeBooking($params);

        $this->assertInstanceOf(Bookings::class, $result);
    }
}
