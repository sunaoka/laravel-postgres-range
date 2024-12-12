<?php

declare(strict_types=1);

namespace Sunaoka\LaravelPostgres\Tests\Types;

use Sunaoka\LaravelPostgres\Tests\TestCase;
use Sunaoka\LaravelPostgres\Types\Bounds\Lower;
use Sunaoka\LaravelPostgres\Types\Bounds\Upper;
use Sunaoka\LaravelPostgres\Types\TsRange;

class TsRangeTest extends TestCase
{
    public function test_to_string(): void
    {
        // [2020-10-01 00:00:00,2020-10-01 23:59:59] -> ["2020-10-01 00:00:00","2020-10-01 23:59:59"]
        $actual = new TsRange('2020-10-01 00:00:00', '2020-10-01 23:59:59', Lower::Inclusive, Upper::Inclusive);
        self::assertSame('["2020-10-01 00:00:00","2020-10-01 23:59:59"]', (string) $actual);

        // [2020-10-01 00:00:00,2020-10-01 23:59:59) -> ["2020-10-01 00:00:00","2020-10-01 23:59:59")
        $actual = new TsRange('2020-10-01 00:00:00', '2020-10-01 23:59:59', Lower::Inclusive, Upper::Exclusive);
        self::assertSame('["2020-10-01 00:00:00","2020-10-01 23:59:59")', (string) $actual);

        // (2020-10-01 00:00:00,2020-10-01 23:59:59] -> ("2020-10-01 00:00:00","2020-10-01 23:59:59"]
        $actual = new TsRange('2020-10-01 00:00:00', '2020-10-01 23:59:59', Lower::Exclusive, Upper::Inclusive);
        self::assertSame('("2020-10-01 00:00:00","2020-10-01 23:59:59"]', (string) $actual);

        // (2020-10-01 00:00:00,2020-10-01 23:59:59) -> ("2020-10-01 00:00:00","2020-10-01 23:59:59")
        $actual = new TsRange('2020-10-01 00:00:00', '2020-10-01 23:59:59', Lower::Exclusive, Upper::Exclusive);
        self::assertSame('("2020-10-01 00:00:00","2020-10-01 23:59:59")', (string) $actual);

        // [2020-10-01 00:00:00,) -> ["2020-10-01 00:00:00",)
        $actual = new TsRange('2020-10-01 00:00:00', null, Lower::Inclusive, Upper::Exclusive);
        self::assertSame('["2020-10-01 00:00:00",)', (string) $actual);

        // (2020-10-01 00:00:00,] -> ("2020-10-01 00:00:00",)
        $actual = new TsRange('2020-10-01 00:00:00', null, Lower::Exclusive, Upper::Inclusive);
        self::assertSame('("2020-10-01 00:00:00",)', (string) $actual);

        // (,2020-10-01 23:59:59] -> (,"2020-10-01 23:59:59"]
        $actual = new TsRange(null, '2020-10-01 23:59:59', Lower::Exclusive, Upper::Inclusive);
        self::assertSame('(,"2020-10-01 23:59:59"]', (string) $actual);

        // [,2020-10-01 23:59:59) -> (,"2020-10-01 23:59:59")
        $actual = new TsRange(null, '2020-10-01 23:59:59', Lower::Inclusive, Upper::Exclusive);
        self::assertSame('(,"2020-10-01 23:59:59")', (string) $actual);

        // [,) -> (,)
        $actual = new TsRange(null, null, Lower::Inclusive, Upper::Exclusive);
        self::assertSame('(,)', (string) $actual);
    }

    public function test_to_array(): void
    {
        // [2020-10-01 00:00:00,2020-10-01 23:59:59] -> ["2020-10-01 00:00:00","2020-10-01 23:59:59"]
        $actual = (new TsRange('2020-10-01 00:00:00', '2020-10-01 23:59:59', Lower::Inclusive, Upper::Inclusive))->toArray();
        self::assertSame('2020-10-01 00:00:00', $actual[0]?->format('Y-m-d H:i:s'));
        self::assertSame('2020-10-01 23:59:59', $actual[1]?->format('Y-m-d H:i:s'));

        // [2020-10-01 00:00:00,2020-10-01 23:59:59) -> ["2020-10-01 00:00:00","2020-10-01 23:59:59")
        $actual = (new TsRange('2020-10-01 00:00:00', '2020-10-01 23:59:59', Lower::Inclusive, Upper::Exclusive))->toArray();
        self::assertSame('2020-10-01 00:00:00', $actual[0]?->format('Y-m-d H:i:s'));
        self::assertSame('2020-10-01 23:59:59', $actual[1]?->format('Y-m-d H:i:s'));

        // (2020-10-01 00:00:00,2020-10-01 23:59:59] -> ("2020-10-01 00:00:00","2020-10-01 23:59:59"]
        $actual = (new TsRange('2020-10-01 00:00:00', '2020-10-01 23:59:59', Lower::Exclusive, Upper::Inclusive))->toArray();
        self::assertSame('2020-10-01 00:00:00', $actual[0]?->format('Y-m-d H:i:s'));
        self::assertSame('2020-10-01 23:59:59', $actual[1]?->format('Y-m-d H:i:s'));

        // (2020-10-01 00:00:00,2020-10-01 23:59:59) -> ("2020-10-01 00:00:00","2020-10-01 23:59:59")
        $actual = (new TsRange('2020-10-01 00:00:00', '2020-10-01 23:59:59', Lower::Exclusive, Upper::Exclusive))->toArray();
        self::assertSame('2020-10-01 00:00:00', $actual[0]?->format('Y-m-d H:i:s'));
        self::assertSame('2020-10-01 23:59:59', $actual[1]?->format('Y-m-d H:i:s'));

        // [2020-10-01 00:00:00,) -> ["2020-10-01 00:00:00",)
        $actual = (new TsRange('2020-10-01 00:00:00', null, Lower::Inclusive, Upper::Exclusive))->toArray();
        self::assertSame('2020-10-01 00:00:00', $actual[0]?->format('Y-m-d H:i:s'));
        self::assertNull($actual[1]);

        // (2020-10-01 00:00:00,] -> ("2020-10-01 00:00:00",)
        $actual = (new TsRange('2020-10-01 00:00:00', null, Lower::Exclusive, Upper::Inclusive))->toArray();
        self::assertSame('2020-10-01 00:00:00', $actual[0]?->format('Y-m-d H:i:s'));
        self::assertNull($actual[1]);

        // (,2020-10-01 23:59:59] -> (,"2020-10-01 23:59:59"]
        $actual = (new TsRange(null, '2020-10-01 23:59:59', Lower::Exclusive, Upper::Inclusive))->toArray();
        self::assertNull($actual[0]);
        self::assertSame('2020-10-01 23:59:59', $actual[1]?->format('Y-m-d H:i:s'));

        // [,2020-10-01 23:59:59) -> (,"2020-10-01 23:59:59")
        $actual = (new TsRange(null, '2020-10-01 23:59:59', Lower::Inclusive, Upper::Exclusive))->toArray();
        self::assertNull($actual[0]);
        self::assertSame('2020-10-01 23:59:59', $actual[1]?->format('Y-m-d H:i:s'));

        // [,) -> (,)
        $actual = (new TsRange(null, null, Lower::Inclusive, Upper::Exclusive))->toArray();
        self::assertNull($actual[0]);
        self::assertNull($actual[1]);
    }
}
