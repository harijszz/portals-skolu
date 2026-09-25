<?php

namespace Database\Seeders;

use App\Models\User;
use Faker\Factory as FakerFactory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

class SchoolDemoSeeder extends Seeder
{
    public function run(): void
    {
        $faker = FakerFactory::create('lv_LV');
        $currentMonth = Carbon::now()->startOfMonth();
        $months = collect(range(5, 0))
            ->map(fn (int $monthsAgo) => $currentMonth->copy()->subMonths($monthsAgo));
        $abilities = [8.2, 3.8, 4.5, 5.4, 6.5, 7.4, 8.5, 9.4, 7.8, 6.2];
        $descriptions = config('curriculum.grade_descriptions');

        foreach ($abilities as $index => $ability) {
            $isDemoStudent = $index === 0;
            $email = $isDemoStudent
                ? 'skolens@example.com'
                : sprintf('skolens%02d@fakelaukums.test', $index);

            $user = User::query()->firstOrNew(['email' => $email]);
            $user->name = $isDemoStudent ? 'Demo skolēns' : $faker->name();
            $user->email_verified_at = now();
            $user->password = Hash::make('password');
            $user->save();
            $user->subjects()->delete();

            $studentAbility = $ability + $faker->randomFloat(1, -0.2, 0.2);

            foreach (config('curriculum.subjects') as $subjectData) {
                $subject = $user->subjects()->create([
                    'name' => $subjectData['name'],
                    'teacher' => $faker->name(),
                    'semester' => $faker->randomElement(['1', '2', 'whole_year']),
                    'credits' => $subjectData['credits'],
                    'passing_grade' => 4,
                ]);
                $subjectAbility = $studentAbility + $faker->randomFloat(1, -0.4, 0.4);

                foreach ($months as $month) {
                    $gradeCount = $faker->numberBetween(1, 3);

                    for ($gradeIndex = 0; $gradeIndex < $gradeCount; $gradeIndex++) {
                        $value = max(1, min(10, $subjectAbility + $faker->randomFloat(1, -1.1, 1.1)));
                        $description = $faker->boolean(65) && $subjectData['topics'] !== []
                            ? 'Kontroldarba: '.$faker->randomElement($subjectData['topics'])
                            : $faker->randomElement($descriptions);
                        $date = $faker->dateTimeBetween(
                            $month->copy()->startOfMonth()->format('Y-m-d'),
                            $month->copy()->endOfMonth()->format('Y-m-d'),
                        );

                        $subject->grades()->create([
                            'value' => round($value, 1),
                            'weight' => $faker->randomElement([1, 1, 1, 2]),
                            'description' => $description,
                            'date' => $date->format('Y-m-d'),
                        ]);
                    }
                }
            }
        }
    }
}
