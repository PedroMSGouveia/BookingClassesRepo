<?php

declare(strict_types=1);

namespace Tests\Feature\Controllers;

use App\Models\Bookings;
use App\Models\Classes;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BookingsControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testGetAllBookings(): void
    {
        Classes::factory()->create(['date' => '2023-12-01']);
        Bookings::factory()->create(['classes_id' => 1]);

        $response = $this->get('/api/bookings');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'classes_id',
                        'person_name',
                        'class' => [
                            'id',
                            'name',
                            'date',
                            'capacity',
                        ]
                    ],
                ]
            ]);
    }

    public function testGetAllBookingsWithPage(): void
    {
        Classes::factory()->create(['date' => '2023-12-01']);
        Bookings::factory()->create(['classes_id' => 2]);

        $response = $this->get('/api/bookings?page=1');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'total',
                'count',
                'data' => [
                    '*' => [
                        'id',
                        'classes_id',
                        'person_name',
                        'class' => [
                            'id',
                            'name',
                            'date',
                            'capacity',
                        ]
                    ],
                ]
            ]);
    }

    public function testStoreBooking(): void
    {
        Classes::factory()->create(['date' => '2023-12-01']);

        $data = [
            'personName' => 'Pedro Gouveia',
            'date' => '2023-12-01'
        ];

        $response = $this->post('/api/bookings', $data);

        $response->assertStatus(201);

        $response->assertJsonStructure([
            'id',
            'classes_id',
            'person_name'
        ]);
    }

    public function testDeleteBooking(): void
    {
        $id = Classes::factory()->create(['date' => '2023-12-01']);
        $booking = Bookings::factory()->create(['classes_id' => $id, 'person_name' => 'Pedro Gouveia']);

        $data = ['date' => '2023-12-01', 'personName' => 'Pedro Gouveia'];

        $response = $this->delete('/api/bookings', $data);

        $response->assertStatus(200);
    }
}
