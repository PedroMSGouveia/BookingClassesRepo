<?php

declare(strict_types=1);

namespace Tests\Feature\Repositories;

use App\Helpers\BookingsHelper;
use App\Helpers\ClassesHelper;
use App\Http\Repositories\BookingsRepository;
use App\Http\Repositories\ClassesRepository;
use App\Models\Bookings;
use App\Models\Classes;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use stdClass;
use Tests\TestCase;

class BookingsRepositoryTest extends TestCase
{
    use RefreshDatabase;

    public function testGetBookingsWithPages(): void
    {
        $repository = new BookingsRepository();

        $class1 = Classes::factory()->create(['date' => '2023-12-01']);
        $booking1 = Bookings::factory(11)->create(['classes_id' => $class1->id]);

        $params = BookingsHelper::withStdClass((object) ['page'=>2]);
        $responseData = $repository->getBookings($params);
        $this->assertEquals(11, $responseData['total']);
        $this->assertCount(1, $responseData['data']);
    }

    public function testGetBookingsWithDates(): void
    {
        $class1 = Classes::factory()->create(['date' => '2023-12-01']);
        $class2 = Classes::factory()->create(['date' => '2023-12-02']);
        $class3 = Classes::factory()->create(['date' => '2023-12-03']);
        $booking1 = Bookings::factory()->create(['classes_id' => $class1->id]);
        $booking2 = Bookings::factory()->create(['classes_id' => $class2->id]);
        $booking3 = Bookings::factory()->create(['classes_id' => $class3->id]);

        $repository = new BookingsRepository();

        $params = BookingsHelper::withDates('2023-12-01', '2023-12-02');
        $responseData = $repository->getBookings($params);
        $this->assertCount(2, $responseData['data']);

        $this->assertEquals($class1->date, $responseData['data'][0]->class->date);
    }

    public function testGetBookingsWithNameFilters(): void
    {
        $class1 = Classes::factory()->create(['date' => '2023-12-01', 'name'=>'Cycling']);
        $class2 = Classes::factory()->create(['date' => '2023-12-02']);
        $booking1 = Bookings::factory()->create(['classes_id' => $class1->id, 'person_name'=>'Pedro Gouveia']);
        $booking2 = Bookings::factory()->create(['classes_id' => $class2->id]);

        $repository = new BookingsRepository();

        $params = BookingsHelper::withStdClass((object) ['personName'=>'Pedro Gouveia', 'className'=>'Cycling']);
        $responseData = $repository->getBookings($params);
        $this->assertCount(1, $responseData['data']);

        $this->assertEquals($params->personName, $responseData['data'][0]->person_name);
        $this->assertEquals($params->className, $responseData['data'][0]->class->name);
    }

    public function testAddBooking(): void
    {
        $class = Classes::factory()->create();

        $repository = new BookingsRepository();

        $personName = 'Pedro Gouveia';
        $classId = $class->id;
        $result = $repository->addBooking($personName, $classId);
        $this->assertInstanceOf(Bookings::class, $result);
        $this->assertEquals($personName, $result->person_name);
        $this->assertEquals($classId, $result->classes_id);
    }

    public function testDeleteBookings(): void
    {
        $class1 = Classes::factory()->create();
        $class2 = Classes::factory()->create();
        $booking1 = Bookings::factory()->create(['classes_id' => $class1->id]);
        $booking2 = Bookings::factory()->create(['classes_id' => $class2->id]);

        $repository = new BookingsRepository();

        $params = BookingsHelper::withStdClass((object)['classesId' => $booking1->classes_id, 'personName' => $booking1->person_name]);
        $result = $repository->deleteBookings($params);
        $this->assertEquals(1, $result);
        $this->assertFalse(Bookings::where('id', $booking1->id)->exists());
        $this->assertTrue(Bookings::where('id', $booking2->id)->exists());
    }

    public function testExistsBookingFromPersonName(): void
    {
        $class = Classes::factory()->create();
        $booking = Bookings::factory()->create(['classes_id' => $class->id]);

        $repository = new BookingsRepository();

        $params = BookingsHelper::withStdClass((object)['classesId' => $booking->classes_id, 'personName' => $booking->person_name]);
        $this->assertTrue($repository->existsBookingFromPersonName($params));

        $params = BookingsHelper::withStdClass((object)['classesId' => $booking->classes_id, 'personName' => 'fake name']);
        $this->assertFalse($repository->existsBookingFromPersonName($params));
    }
}
