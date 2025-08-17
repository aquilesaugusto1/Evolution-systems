<?php

namespace App\Traits;

trait ConvertsTime
{
    public static function decimalToTime(float $decimalHours): string
    {
        if ($decimalHours < 0) {
            $isNegative = true;
            $decimalHours = abs($decimalHours);
        } else {
            $isNegative = false;
        }

        $hours = floor($decimalHours);
        $minutes = round(($decimalHours - $hours) * 60);

        if ($minutes >= 60) {
            $hours += floor($minutes / 60);
            $minutes %= 60;
        }

        $timeString = sprintf('%d:%02d', $hours, $minutes);

        return $isNegative ? '-' . $timeString : $timeString;
    }

    public static function timeToDecimal(string $time): float
    {
        $isNegative = false;
        if (strpos($time, '-') === 0) {
            $isNegative = true;
            $time = substr($time, 1);
        }

        if (strpos($time, ':') === false) {
            $decimal = (float) $time;
            return $isNegative ? -$decimal : $decimal;
        }

        [$hours, $minutes] = explode(':', $time);
        $decimal = (float) $hours + ((float) $minutes / 60);

        return $isNegative ? -$decimal : $decimal;
    }
}
