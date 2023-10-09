<?php

namespace Database\Seeders;

use App\Models\Bookings;
use Illuminate\Database\Seeder;

class BookingsTableSeeder extends Seeder
{
    public function run()
    {
        Bookings::factory()->count(30)->create();
    }
}
