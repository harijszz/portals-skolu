<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class SubjectFactory extends Factory
{
    public function definition(): array
    {
        $subject = fake('lv_LV')->randomElement(config('curriculum.subjects'));

        return [
            'user_id' => User::factory(),
            'name' => $subject['name'],
            'teacher' => fake('lv_LV')->name(),
            'semester' => fake()->randomElement(['1', '2', 'whole_year']),
            'credits' => $subject['credits'],
            'passing_grade' => 4,
        ];
    }
}
