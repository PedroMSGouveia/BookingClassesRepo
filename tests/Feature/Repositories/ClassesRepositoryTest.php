<?php

declare(strict_types=1);

namespace Tests\Feature\Repositories;

use App\Helpers\ClassesHelper;
use App\Http\Repositories\ClassesRepository;
use App\Models\Classes;
use App\Models\Bookings;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ClassesRepositoryTest extends TestCase
{
    use RefreshDatabase;

    public function testAddClass(): void
    {

        $repository = new ClassesRepository();
        $result = $repository->addClass('Cycling', '2023-12-01', 30);

        $this->assertInstanceOf(Classes::class, $result);

        $this->assertEquals('Cycling', $result->name);
        $this->assertEquals('2023-12-01', $result->date);
        $this->assertEquals(30, $result->capacity);
    }

    public function testDeleteClasses(): void
    {

        Classes::factory()->create(['date' => '2023-12-01']);
        Classes::factory()->create(['date' => '2023-12-02']);

        $repository = new ClassesRepository();
        $result = $repository->deleteClasses(ClassesHelper::withDates('2023-12-01' , '2023-12-02'));

        $this->assertIsInt($result);
        $this->assertEquals(2, $result);
    }

    public function testDeleteClassesWithBookings(): void
    {

        $class = Classes::factory()->create(['date' => '2023-12-01']);
        Bookings::factory()->create(['classes_id' => $class->id, 'person_name' => 'Pedro Gouveia']);
        Bookings::factory()->create(['classes_id' => $class->id, 'person_name' => 'Carlos Gouveia']);

        $repository = new ClassesRepository();
        $result = $repository->deleteClasses(ClassesHelper::withDates('2023-12-01' , '2023-12-01'));

        $this->assertIsInt($result);
        $this->assertEquals(1, $result);
    }

    public function testGetClassIdByDate(): void
    {
        $class = Classes::factory()->create(['date' => '2023-12-01']);

        $repository = new ClassesRepository();

        $classId = $repository->getClassIdByDate('2023-12-01');
        $this->assertEquals($class->id, $classId);
    }

    public function testExistsClassesFromDate(): void
    {
        $class = Classes::factory()->create(['date' => '2023-12-01']);

        $repository = new ClassesRepository();

        $this->assertTrue($repository->existsClassesFromDate('2023-12-01'));

        $this->assertFalse($repository->existsClassesFromDate('2023-12-02'));
    }

    public function testExistsClassesFromDateInterval(): void
    {
        Classes::factory()->create(['date' => '2023-12-01']);
        Classes::factory()->create(['date' => '2023-12-02']);
        Classes::factory()->create(['date' => '2023-12-03']);

        $repository = new ClassesRepository();

        $data = ClassesHelper::withDates('2023-12-01', '2023-12-03');

        $this->assertTrue($repository->existsClassesFromDateInterval($data));

        $data = ClassesHelper::withDates('2023-12-04', '2023-12-05');
        $this->assertFalse($repository->existsClassesFromDateInterval($data));
    }
}
