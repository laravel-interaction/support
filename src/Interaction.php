<?php

declare(strict_types=1);

namespace LaravelInteraction\Support;

class Interaction
{
    /**
     * @var array<int, string>
     */
    private static array $divisorMap = [
        1000 ** 0 => '',
        1000 ** 1 => 'K',
        1000 ** 2 => 'M',
        1000 ** 3 => 'B',
        1000 ** 4 => 'T',
        1000 ** 5 => 'Qa',
        1000 ** 6 => 'Qi',
    ];

    /**
     * @param array<int, string> $divisorMap
     */
    public static function divisorMap(array $divisorMap): void
    {
        self::$divisorMap = $divisorMap;
    }

    /**
     * @phpstan-param 1|2|3|4 $mode
     *
     * @param array<int, string>|null $divisorMap
     */
    public static function numberForHumans(
        float|int $number,
        int $precision = 1,
        int $mode = PHP_ROUND_HALF_UP,
        ?array $divisorMap = []
    ): string {
        $divisorMap = $divisorMap ?: self::$divisorMap;
        $divisors = array_filter(array_keys($divisorMap), static fn ($divisor): bool => $divisor <= abs($number));
        $divisor = end($divisors) ?: 1;
        $suffix = $divisorMap[$divisor] ?? '';
        if ($divisor === 1) {
            return $number . $suffix;
        }

        return number_format(round($number / $divisor, $precision, $mode), $precision) . $suffix;
    }
}
