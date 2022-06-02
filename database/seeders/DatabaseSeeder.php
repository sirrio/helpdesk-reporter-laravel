<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::factory()->create([
            'name' => env('ADMIN_NAME', 'admin'),
            'email' => env('ADMIN_MAIL', 'admin@admin.de'),
            'password' => Hash::make(env('ADMIN_PASSWORD', 'nail-hammer-machine')),
            'isAdmin' => true,
            'isMod' => true,
        ]);

        User::factory()->create([
            'name' => 'David',
            'email' => 'home@sirrio.de',
            'password' => Hash::make(env('ADMIN_PASSWORD', 'nail-hammer-machine')),
            'isAdmin' => false,
            'isMod' => true,
        ]);

        User::factory(4)->create();

        $this->call([
            DegreeSeeder::class,
            FacultySeeder::class,
            AttendanceSeeder::class,
            SemesterSeeder::class
        ]);
    }
}
