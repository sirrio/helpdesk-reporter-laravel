<?php

namespace Database\Factories;

use App\Models\Degree;
use App\Models\Faculty;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Attendance>
 */
class AttendanceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $starts_at = Carbon::createFromTimestamp($this->faker->dateTimeBetween('-1 year', 'now')->getTimeStamp());
        $ends_at = Carbon::createFromFormat('Y-m-d H:i:s', $starts_at)->addHours($this->faker->numberBetween(1, 2));

        return [
            'user_id' => User::inRandomOrder()->first()->id,
            'semester' => $this->faker->randomElement(['SS22', 'WS2122', 'SS21', 'WS2021']),
            'date' => $this->faker->dateTimeThisMonth(),
            'startTime' => $starts_at,
            'endTime' => $ends_at,
            'degree' => Degree::inRandomOrder()->first()->name,
            'faculty' => Faculty::inRandomOrder()->first()->name,
            'mathBasic' => $this->faker->boolean(),
            'mathLow' => $this->faker->boolean(),
            'mathHigh' => $this->faker->boolean(),
            'programming' => $this->faker->boolean(),
            'physics' => $this->faker->boolean(),
            'chemistry' => $this->faker->boolean(),
            'organization' => $this->faker->boolean(),
        ];
    }
}
