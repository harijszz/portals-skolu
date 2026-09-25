<?php

namespace Tests\Feature;

use App\Models\Grade;
use App\Models\Subject;
use App\Models\User;
use App\Services\StudentMonthlyMetricsService;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentMonthlyMetricsServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_calculates_an_unweighted_average_from_only_the_users_month_grades(): void
    {
        $user = User::factory()->create();
        $subject = Subject::factory()->for($user)->create();
        $otherUser = User::factory()->create();
        $otherSubject = Subject::factory()->for($otherUser)->create();

        Grade::factory()->for($subject)->create([
            'value' => 4,
            'weight' => 10,
            'date' => '2026-09-01',
        ]);
        Grade::factory()->for($subject)->create([
            'value' => 10,
            'weight' => 0.1,
            'date' => '2026-09-30',
        ]);
        Grade::factory()->for($subject)->create([
            'value' => 1,
            'date' => '2026-08-31',
        ]);
        Grade::factory()->for($subject)->create([
            'value' => 1,
            'date' => null,
        ]);
        Grade::factory()->for($otherSubject)->create([
            'value' => 1,
            'date' => '2026-09-15',
        ]);

        $metrics = app(StudentMonthlyMetricsService::class)->forMonth(
            $user,
            CarbonImmutable::parse('2026-09-01'),
        );

        $this->assertSame(7.0, $metrics->average);
        $this->assertSame(65.57, $metrics->scholarship->amount);
        $this->assertCount(2, $metrics->grades);
        $this->assertCount(1, $metrics->subjectAverages);
        $this->assertSame(7.0, $metrics->subjectAverages->first()['average']);
    }

    public function test_it_builds_twelve_months_of_history_in_reverse_order(): void
    {
        $user = User::factory()->create();
        $subject = Subject::factory()->for($user)->create();

        Grade::factory()->for($subject)->create([
            'value' => 8,
            'date' => '2026-09-10',
        ]);
        Grade::factory()->for($subject)->create([
            'value' => 6,
            'date' => '2026-08-10',
        ]);

        $history = app(StudentMonthlyMetricsService::class)->history(
            $user,
            CarbonImmutable::parse('2026-09-01'),
        );

        $this->assertCount(12, $history);
        $this->assertSame('2026-09', $history->first()->month->format('Y-m'));
        $this->assertSame('2025-10', $history->last()->month->format('Y-m'));

        $august = $history->first(
            fn ($metrics) => $metrics->month->format('Y-m') === '2026-08',
        );

        $this->assertSame(6.0, $august->average);
        $this->assertSame(51.0, $august->scholarship->amount);
    }
}
