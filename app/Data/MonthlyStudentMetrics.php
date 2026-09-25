<?php

namespace App\Data;

use Carbon\CarbonImmutable;
use Illuminate\Support\Collection;

final readonly class MonthlyStudentMetrics
{
    public function __construct(
        public CarbonImmutable $month,
        public ?float $average,
        public ScholarshipResult $scholarship,
        public Collection $grades,
        public Collection $subjectAverages,
    ) {}
}
