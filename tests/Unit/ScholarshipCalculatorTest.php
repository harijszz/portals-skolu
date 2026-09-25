<?php

namespace Tests\Unit;

use App\Services\ScholarshipCalculator;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class ScholarshipCalculatorTest extends TestCase
{
    #[DataProvider('scholarshipCases')]
    public function test_it_calculates_scholarship_for_average(?float $average, float $expectedAmount): void
    {
        $result = app(ScholarshipCalculator::class)->calculate($average);

        $this->assertSame($expectedAmount, $result->amount);
    }

    public static function scholarshipCases(): array
    {
        return [
            'bez atzīmēm' => [null, 0.0],
            'zem četrām' => [3.99, 0.0],
            'četru apakšrobeža' => [4.0, 16.0],
            'pirmā diapazona vidus' => [4.5, 23.07],
            'pirmā diapazona beigas' => [4.99, 30.0],
            'otro diapazonu sākums' => [5.0, 31.0],
            'otro diapazonu beigas' => [5.99, 50.0],
            'trešā diapazona vidus' => [7.0, 65.57],
            'trešā diapazona beigas' => [7.99, 80.0],
            'ceturtā diapazona beigas' => [8.99, 100.0],
            'piektā diapazona vidus' => [9.5, 110.5],
            'desmit punkti' => [10.0, 120.0],
            'noapalošana uz leju' => [4.994, 30.0],
            'noapalošana uz augšu' => [4.995, 31.0],
        ];
    }

    public function test_it_returns_the_matching_amount_range(): void
    {
        $result = app(ScholarshipCalculator::class)->calculate(8.5);

        $this->assertSame(81.0, $result->minimumAmount);
        $this->assertSame(100.0, $result->maximumAmount);
    }
}
