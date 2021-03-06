<?php

namespace Database\Seeders;

use App\Models\Faculty;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FacultySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faculties = array(
            "Mathematik",
            "Architektur und Bauingenieurwesen",
            "Sozialwesen",
            "Design Informatik Medien (DCSM)",
            "Wiesbaden Business School",
            "Ingenieurwissenschaften",
            "Keine Angabe",
        );

        foreach ($faculties as $faculty) {
            Faculty::create([
                'name' => $faculty
            ]);
        }
    }
}
