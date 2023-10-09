<?php

namespace Database\Seeders;

use App\Models\Classes;
use Illuminate\Database\Seeder;

class ClassesTableSeeder extends Seeder
{
    public function run()
    {
        $numberOfClasses = 10;

        \App\Models\Classes::factory($numberOfClasses)->create();
    }
}
