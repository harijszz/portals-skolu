<?php

namespace App\Services;

use App\Data\MonthlyStudentMetrics;
use App\Models\Grade;
use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Support\Collection;

class StudentMonthlyMetricsService
{
    public function __construct(private readonly ScholarshipCalculator $scholarshipCalculator) {}

    public function forMonth(User $user, CarbonImmutable $month): MonthlyStudentMetrics
    {
        $month = $month->startOfMonth();
        $grades = $this->gradesBetween($user, $month, $month);

        return $this->buildMetrics($month, $grades);
    }

    public function history(User $user, CarbonImmutable $month, int $months = 12): Collection
    {
        $months = max(1, min(24, $months));
        $month = $month->startOfMonth();
        $firstMonth = $month->subMonths($months - 1);
        $grades = $this->gradesBetween($user, $firstMonth, $month);
        $gradesByMonth = $grades->groupBy(fn (Grade $grade) => $grade->date->format('Y-m'));

        return collect(range(0, $months - 1))
            ->map(function (int $monthsAgo) use ($gradesByMonth, $month) {
                $historyMonth = $month->subMonths($monthsAgo);

                return $this->buildMetrics(
                    $historyMonth,
                    $gradesByMonth->get($historyMonth->format('Y-m'), collect()),
                );
            })
            ->sortByDesc(fn (MonthlyStudentMetrics $metrics) => $metrics->month->format('Y-m'))
            ->values();
    }

    private function gradesBetween(User $user, CarbonImmutable $firstMonth, CarbonImmutable $lastMonth): Collection
    {
        return $user->grades()
            ->whereNotNull('date')
            ->whereBetween('date', [
                $firstMonth->copy()->startOfMonth()->toDateString(),
                $lastMonth->copy()->endOfMonth()->toDateString(),
            ])
            ->with('subject:id,name')
            ->oldest('date')
            ->get();
    }

    private function buildMetrics(CarbonImmutable $month, Collection $grades): MonthlyStudentMetrics
    {
        $average = $this->average($grades);
        $subjectAverages = $grades
            ->groupBy('subject_id')
            ->map(function (Collection $subjectGrades) {
                return [
                    'name' => $subjectGrades->first()->subject->name,
                    'average' => $this->average($subjectGrades),
                    'grade_count' => $subjectGrades->count(),
                ];
            })
            ->sortBy('name')
            ->values();

        return new MonthlyStudentMetrics(
            month: $month,
            average: $average,
            scholarship: $this->scholarshipCalculator->calculate($average),
            grades: $grades,
            subjectAverages: $subjectAverages,
        );
    }

    private function average(Collection $grades): ?float
    {
        if ($grades->isEmpty()) {
            return null;
        }

        return round($grades->avg(fn (Grade $grade) => (float) $grade->value), 2);
    }
}
