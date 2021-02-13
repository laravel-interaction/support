<?php

declare(strict_types=1);

namespace LaravelInteraction\Support\Tests;

use LaravelInteraction\Support\Interaction;

class InteractionTest extends TestCase
{
    public function data(): array
    {
        return [
            [0, '0', '0', '0', '0a'],
            [1, '1', '1', '1', '1a'],
            [12, '12', '12', '12', '12a'],
            [123, '123', '123', '123', '123a'],
            [12345, '12.3K', '12.35K', '12.34K', '12345a'],
            [1234567, '1.2M', '1.23M', '1.23M', '1234567a'],
            [123456789, '123.5M', '123.46M', '123.46M', '123456789a'],
            [12345678901, '12.3B', '12.35B', '12.35B', '12345678901a'],
            [1234567890123, '1.2T', '1.23T', '1.23T', '1234567890123a'],
            [1234567890123456, '1.2Qa', '1.23Qa', '1.23Qa', '1234567890123456a'],
            [1234567890123456789, '1.2Qi', '1.23Qi', '1.23Qi', '1234567890123456789a'],
        ];
    }

    /**
     * @dataProvider data
     *
     * @param mixed $actual
     * @param mixed $onePrecision
     * @param mixed $twoPrecision
     * @param mixed $halfDown
     * @param mixed $universalSuffix
     */
    public function testNumberForHuman($actual, $onePrecision, $twoPrecision, $halfDown, $universalSuffix): void
    {
        $divisors = [
            1000 ** 0 => '',
            1000 ** 1 => 'K',
            1000 ** 2 => 'M',
            1000 ** 3 => 'B',
            1000 ** 4 => 'T',
            1000 ** 5 => 'Qa',
            1000 ** 6 => 'Qi',
        ];
        self::assertSame($onePrecision, Interaction::numberForHuman($actual, 1, PHP_ROUND_HALF_UP, $divisors));
        self::assertSame($twoPrecision, Interaction::numberForHuman($actual, 2, PHP_ROUND_HALF_UP, $divisors));
        self::assertSame($halfDown, Interaction::numberForHuman($actual, 2, PHP_ROUND_HALF_DOWN, $divisors));
        self::assertSame(
            $universalSuffix,
            Interaction::numberForHuman(
                $actual,
                2,
                PHP_ROUND_HALF_DOWN,
                [
                    1 => 'a',
                ]
            )
        );
    }
}
