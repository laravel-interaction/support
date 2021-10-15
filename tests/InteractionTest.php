<?php

declare(strict_types=1);

namespace LaravelInteraction\Support\Tests;

use LaravelInteraction\Support\Interaction;

/**
 * @internal
 */
final class InteractionTest extends TestCase
{
    /**
     * @var array<int, string>
     */
    private const DIVISORS = [
        1000 ** 0 => '',
        1000 ** 1 => 'K',
        1000 ** 2 => 'M',
        1000 ** 3 => 'B',
        1000 ** 4 => 'T',
        1000 ** 5 => 'Qa',
        1000 ** 6 => 'Qi',
    ];

    public function data(): \Iterator
    {
        yield [0, '0', '0', '0', '0a'];
        yield [1, '1', '1', '1', '1a'];
        yield [12, '12', '12', '12', '12a'];
        yield [12.1, '12.1', '12.1', '12.1', '12.1a'];
        yield [123, '123', '123', '123', '123a'];
        yield [12345, '12.3K', '12.35K', '12.34K', '12345a'];
        yield [1234567, '1.2M', '1.23M', '1.23M', '1234567a'];
        yield [123456789, '123.5M', '123.46M', '123.46M', '123456789a'];
        yield [12345678901, '12.3B', '12.35B', '12.35B', '12345678901a'];
        yield [1234567890123, '1.2T', '1.23T', '1.23T', '1234567890123a'];
        yield [1234567890123456, '1.2Qa', '1.23Qa', '1.23Qa', '1234567890123456a'];
        yield [1234567890123456789, '1.2Qi', '1.23Qi', '1.23Qi', '1234567890123456789a'];
    }

    /**
     * @dataProvider data
     *
     * @param mixed $actual
     */
    public function testNumberForHuman(
        $actual,
        string $onePrecision,
        string $twoPrecision,
        string $halfDown,
        string $universalSuffix
    ): void {
        self::assertSame($onePrecision, Interaction::numberForHumans($actual, 1, PHP_ROUND_HALF_UP, self::DIVISORS));
        self::assertSame($twoPrecision, Interaction::numberForHumans($actual, 2, PHP_ROUND_HALF_UP, self::DIVISORS));
        self::assertSame($halfDown, Interaction::numberForHumans($actual, 2, PHP_ROUND_HALF_DOWN, self::DIVISORS));
        Interaction::divisorMap(self::DIVISORS);
        self::assertSame($halfDown, Interaction::numberForHumans($actual, 2, PHP_ROUND_HALF_DOWN));
        self::assertSame(
            $universalSuffix,
            Interaction::numberForHumans($actual, 2, PHP_ROUND_HALF_DOWN, [
                1 => 'a',
            ])
        );
    }
}
