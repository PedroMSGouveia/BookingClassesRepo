<?php

namespace Database\Factories;

use App\Models\Bookings;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Bookings>
 */
class BookingsFactory extends Factory
{
    protected $model = Bookings::class;

    public function definition()
    {
        return [
            'classes_id' => $this->faker->numberBetween(1, 10),
            'person_name' => $this->faker->name,
        ];
    }
}
