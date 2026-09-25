<?php

namespace Database\Factories;

use App\Models\Subject;
use Illuminate\Database\Eloquent\Factories\Factory;

class GradeFactory extends Factory
{
    public function definition(): array
    {
        return [
            'subject_id' => Subject::factory(),
            'value' => fake('lv_LV')->randomFloat(1, 1, 10),
            'weight' => fake()->randomElement([1, 1, 2, 3]),
            'description' => fake('lv_LV')->randomElement(config('curriculum.grade_descriptions')),
            'date' => fake('lv_LV')->dateTimeBetween('-6 months', 'now')->format('Y-m-d'),
        ];
    }
}
