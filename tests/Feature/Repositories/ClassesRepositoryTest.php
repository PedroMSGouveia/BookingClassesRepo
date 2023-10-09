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

    public function testGetClassesWithPages(): void
    {
        $repository = new ClassesRepository();

        Classes::factory(11)->create();

        $params = ClassesHelper::withStdClass((object) ['page'=>2]);
        $responseData = $repository->getClasses($params);
        $this->assertEquals(11, $responseData['total']);
        $this->assertCount(1, $responseData['data']);
    }

    public function testGetClassesWithName(): void
    {
        $repository = new ClassesRepository();

        Classes::factory(2)->create(['name'=>'Cycling']);
        Classes::factory(10)->create(['name'=>'Zumba']);

        $params = ClassesHelper::withStdClass((object) ['name'=>'Cycling']);
        $responseData = $repository->getClasses($params);
        $this->assertCount(2, $responseData['data']);
        $this->assertEquals($params->name, $responseData['data'][0]->name);
    }

    public function testGetClassesWithDates(): void
    {
        $repository = new ClassesRepository();

        Classes::factory()->create(['date'=>'2023-12-01']);
        Classes::factory()->create(['date'=>'2023-12-02']);
        Classes::factory()->create(['date'=>'2023-12-03']);
        Classes::factory()->create(['date'=>'2023-12-04']);

        $params = ClassesHelper::withDates('2023-12-01', '2023-12-02');
        $responseData = $repository->getClasses($params);
        $this->assertCount(2, $responseData['data']);

        $params = ClassesHelper::withStdClass((object)['startDate'=>'2023-12-01']);
        $responseData = $repository->getClasses($params);
        $this->assertCount(4, $responseData['data']);

        $params = ClassesHelper::withStdClass((object)['startDate'=>'2023-12-04']);
        $responseData = $repository->getClasses($params);
        $this->assertCount(1, $responseData['data']);

        $params = ClassesHelper::withStdClass((object)['endDate'=>'2023-12-03']);
        $responseData = $repository->getClasses($params);
        $this->assertCount(3, $responseData['data']);
    }

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
