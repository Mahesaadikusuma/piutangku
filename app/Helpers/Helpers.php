<?php

namespace App\Helpers;

use Carbon\Carbon;

class Helpers
{
    public static function getYears(int $startYear = 1945, ?int $endYear = null): array
    {
        $endYear = $endYear ?? Carbon::now()->year;
        $years = [];

        for ($year = $endYear; $year >= $startYear; $year--) {
            $years[] = $year;
        }

        return $years;
    }

    public static function getMonths(): array
    {
        $months = [];

        for ($i = 1; $i <= 12; $i++) {
            $months[$i] = Carbon::create()->month($i)->format('F');
        }

        return $months;
    }
}
