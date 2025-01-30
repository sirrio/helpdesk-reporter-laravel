<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
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

        //        User::factory(4)->create();

        $this->call([
            DegreeSeeder::class,
            FacultySeeder::class,
            SemesterSeeder::class,
            AttendanceSeeder::class,
        ]);
    }
}
