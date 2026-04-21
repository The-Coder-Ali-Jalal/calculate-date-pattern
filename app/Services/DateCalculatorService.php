<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use DateTime;

class DateCalculatorService
{
   
    public function getMatches(string $day, int $date, int $startYear, int $endYear): array
    {
        $allMatches = [];
        $keys = [];

        // prepare the keys for Cache::many()
        for ($year = $startYear; $year <= $endYear; $year++) {
            $keys[$year] = "cal:$year:$day:$date";
        }

        $cachedData = Cache::many(array_values($keys));
       
        $allMatches=[];
        foreach ($keys as $year => $key) {
            $yearResults = $cachedData[$key] ?? null;
            if ($yearResults === null) {
                // cache miss: calculate and store
                $yearResults = $this->calculateYearMatches($year, $day, $date);
                Cache::forever($key, $yearResults);
            }

            array_push($allMatches, ...$yearResults);
        }

        return $allMatches;
        // return $cachedData;
    }

   
    private function calculateYearMatches(int $year, string $day, int $dayOfMonth): array
    {
        $matches = [];
        for ($month = 1; $month <= 12; $month++) {
            if (!checkdate($month, $dayOfMonth, $year)) continue;
            $dt = new DateTime("$year-$month-$dayOfMonth");
            if ($dt->format('l') === ucfirst(strtolower($day))) {
                $matches[] = $dt->format('M-Y');
            }
        }
        return $matches;
    }
}