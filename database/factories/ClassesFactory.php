<?php

namespace Database\Factories;

use App\Models\Classes;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Classes>
 */
class ClassesFactory extends Factory
{
    protected $model = Classes::class;

    public function definition()
    {
        $startDate = Carbon::parse("2023-12-01");
        $endDate = Carbon::parse("2023-12-10");

        return [
            'name' => $this->faker->word,
            'date' => $this->faker->unique()->dateTimeBetween($startDate, $endDate)->format('Y-m-d'),
            'capacity' => $this->faker->numberBetween(10, 30),
        ];
    }
}
