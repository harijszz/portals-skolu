<?php

namespace App\Services;

use App\Data\ScholarshipResult;

class ScholarshipCalculator
{
    public function calculate(?float $average): ScholarshipResult
    {
        if ($average === null) {
            return new ScholarshipResult(null, 0);
        }

        $average = round($average, 2);
        $minimumAverage = (float) config('scholarships.minimum_average');

        if ($average < $minimumAverage) {
            return new ScholarshipResult($average, 0);
        }

        foreach (config('scholarships.bands', []) as $band) {
            if ($average < $band['minimum_average'] || $average > $band['maximum_average']) {
                continue;
            }

            $minimumAmount = (float) $band['minimum_amount'];
            $maximumAmount = (float) $band['maximum_amount'];
            $range = (float) $band['maximum_average'] - (float) $band['minimum_average'];
            $progress = $range > 0 ? ($average - $band['minimum_average']) / $range : 0;
            $amount = round($minimumAmount + $progress * ($maximumAmount - $minimumAmount), 2);

            return new ScholarshipResult($average, $amount, $minimumAmount, $maximumAmount);
        }

        return new ScholarshipResult($average, 0);
    }
}
