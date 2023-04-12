<?php

namespace Database\Seeders;

use App\Models\Semester;
use Illuminate\Database\Seeder;

class SemesterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Semester::factory()->create([
            'semester' => 'SS22',
            'start' => '2022-04-01',
            'end' => '2022-09-30',
        ]);

        Semester::factory()->create([
            'semester' => 'WS2122',
            'start' => '2021-10-01',
            'end' => '2022-03-31',
        ]);

        Semester::factory()->create([
            'semester' => 'SS21',
            'start' => '2021-04-01',
            'end' => '2021-09-30',
        ]);

        Semester::factory()->create([
            'semester' => 'WS2021',
            'start' => '2020-10-01',
            'end' => '2021-03-31',
        ]);
    }
}
