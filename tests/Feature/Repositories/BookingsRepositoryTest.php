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

    public function testGetBookings(): void
    {
        $class1 = Classes::factory()->create(['date' => '2023-12-01']);
        $class2 = Classes::factory()->create(['date' => '2023-12-02']);
        $booking1 = Bookings::factory()->create(['classes_id' => $class1->id]);
        $booking2 = Bookings::factory()->create(['classes_id' => $class2->id]);

        $repository = new BookingsRepository();

        $params = BookingsHelper::withDates('2023-12-01', '2023-12-02');
        $responseData = $repository->getBookings($params);
        $this->assertCount(2, $responseData['data']);


        $this->assertEquals($booking1->id, $responseData['data'][0]->id);
        $this->assertEquals($booking1->name, $responseData['data'][0]->name);
        $this->assertEquals($booking1->capacity, $responseData['data'][0]->capacity);//aqui é que vais comparar os campos todos da tabela para ver se dão match com a estrutura esperada. Só assim tens um teste de repositorio...


        $class1 = Bookings::factory(10)->create(['classes_id' => $class1->id]);
        $params = BookingsHelper::withStdClass((object) ['page'=>1]);
        $responseData = $repository->getBookings($params);
        $this->assertEquals(12, $responseData['total']);
        $this->assertCount(10, $responseData['data']);
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