<?php

namespace App\Data;

final readonly class ScholarshipResult
{
    public function __construct(
        public ?float $average,
        public float $amount,
        public ?float $minimumAmount = null,
        public ?float $maximumAmount = null,
    ) {}
}
