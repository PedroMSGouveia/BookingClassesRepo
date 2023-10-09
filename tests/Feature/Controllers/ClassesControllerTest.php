<?php

declare(strict_types=1);

namespace Tests\Feature\Controllers;

use App\Models\Classes;
use App\Models\Bookings;
use Database\Factories\ClassesFactory;
use Database\Seeders\ClassesTableSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ClassesControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testGetAllClasses(): void
    {
        $classes = Classes::factory(10)->create();
        Bookings::factory(2)->create(['classes_id'=>$classes[0]->id]);

        $response = $this->get('/api/classes');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'date',
                        'capacity',
                        'bookings'
                    ],
                ]
            ]);

        $response ->assertJson([
            'data' => $classes->toArray()
        ]);

        //dd($response); assertEquals com o primeiro inserted
    }

    public function testGetAllClassesWithPage(): void
    {
        $classes = Classes::factory(10)->create();
        Bookings::factory(2)->create(['classes_id'=>$classes[0]->id]);

        $response = $this->get('/api/classes?page=1');

        $response->assertStatus(200)
            ->assertJsonStructure([ //tipos
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'date',
                        'capacity',
                        'bookings'
                    ],
                ]
            ]
        );

        $response ->assertJson([
            'data' => $classes->toArray()
        ]);
    }

    public function testGetAllClassesWithName(): void
    {
        $classesCycling = Classes::factory(5)->create(['name'=>'Cycling']);
        $classesZumba = Classes::factory(5)->create(['name'=>'Zumba']);
        Bookings::factory(2)->create(['classes_id'=>$classesCycling[0]->id]);

        $className = 'Cycling';
        $response = $this->get('/api/classes?name='.$className);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'date',
                        'capacity',
                        'bookings'
                    ],
                ]
            ]);

        $response ->assertJson([
            'data' => $classesCycling->toArray()
        ]);

        $response ->assertJsonMissing([
            'data' => $classesZumba->toArray()
        ]);
    }

    public function testStoreClass(): void
    {
        $data = [
            'name' => 'Cycling',
            'startDate' => '2023-12-01',
            'endDate' => '2023-12-15',
            'capacity' => 30
        ];

        $response = $this->post('/api/classes', $data);

        $response->assertStatus(201);

        $response->assertJsonCount(15);

        $response->assertJsonStructure([
            '*' => [
                'id',
                'name',
                'date',
                'capacity'
            ],
        ]);
    }

    public function testDeleteClassesByDateRange(): void
    {

        Classes::factory()->create(['date' => '2023-12-01']);
        Classes::factory()->create(['date' => '2023-12-02']);

        $data = ['startDate' => '2023-12-01', 'endDate' => '2023-12-02'];

        $response = $this->delete('/api/classes', $data);

        $response->assertStatus(200);

        $response->assertJson([
            "deletedCount" => 2
        ]);
    }
}
