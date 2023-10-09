<?php

declare(strict_types=1);

namespace Tests\Feature\Services;

use Mockery;
use Tests\TestCase;
use App\Helpers\ClassesHelper;
use App\Http\Repositories\ClassesRepository;
use App\Http\Services\ClassesService;
use App\Models\Classes;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;

class ClassesServicesTest extends TestCase
{
    public function testGetAllClasses(): void
    {
        $repository = $this->createMock(ClassesRepository::class);

        $service = new ClassesService($repository);

        $params = ClassesHelper::withStdClass((object) ['page'=>1]);

        $paginator = $this->createMock(LengthAwarePaginator::class);

        $repository->expects($this->once())
            ->method('getClasses')
            ->with($params)
            ->willReturn($paginator);

        $result = $service->getAllClasses($params);

        $this->assertSame($paginator, $result);
    }

    public function testAddClasses(): void
    {
        $repository = $this->createMock(ClassesRepository::class);

        $service = new ClassesService($repository);

        $data = ClassesHelper::withStdClass((object) ['name'=>'Cycling', 'startDate'=>'2023-12-01', 'endDate'=>'2023-12-03', 'capacity'=>20]);

        $fakeClass = new Classes();

        $repository->expects($this->exactly(3))
            ->method('addClass')
            ->willReturn($fakeClass); 

        $createdClasses = $service->addClasses($data);

        $this->assertCount(3, $createdClasses);
    }

    public function testDeleteClass(): void
    {
        $repository = $this->createMock(ClassesRepository::class);

        $service = new ClassesService($repository);

        $data = ClassesHelper::withStdClass((object) ['startDate'=>'2023-12-01', 'endDate'=>'2023-12-03']);

        $repository->expects($this->once())
            ->method('deleteClasses')
            ->with($data)
            ->willReturn(3);

        $deletedRows = $service->deleteClass($data);

        $this->assertEquals(3, $deletedRows);
    }

    public function testGetClassIdByDate(): void
    {
        $repository = $this->createMock(ClassesRepository::class);

        $service = new ClassesService($repository);

        $repository->expects($this->once())
            ->method('getClassIdByDate')
            ->with('2023-12-01')
            ->willReturn(1);

        $classId = $service->getClassIdByDate('2023-12-01');

        $this->assertEquals(1, $classId);
    }

}
