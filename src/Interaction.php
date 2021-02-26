<?php

declare(strict_types=1);

namespace LaravelInteraction\Support;

class Interaction
{
    public static function numberForHumans($number, int $precision = 1, int $mode = PHP_ROUND_HALF_UP, array $divisorMap = []): string
    {
        $divisors = array_filter(
            array_keys($divisorMap),
            static function ($divisor) use ($number) {
                return $divisor <= abs($number);
            }
        );
        $divisor = end($divisors) ?: 1;
        $suffix = $divisorMap[$divisor] ?? '';
        if ($divisor === 1) {
            return $number . $suffix;
        }

        return number_format(round($number / $divisor, $precision, $mode), $precision) . $suffix;
    }
}
