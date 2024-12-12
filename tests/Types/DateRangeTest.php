<?php

declare(strict_types=1);

namespace Sunaoka\LaravelPostgres\Tests\Types;

use Sunaoka\LaravelPostgres\Tests\TestCase;
use Sunaoka\LaravelPostgres\Types\Bounds\Lower;
use Sunaoka\LaravelPostgres\Types\Bounds\Upper;
use Sunaoka\LaravelPostgres\Types\DateRange;

class DateRangeTest extends TestCase
{
    public function test_to_string(): void
    {
        // [2020-10-01,2020-10-03] -> [2020-10-01,2020-10-04)
        $actual = new DateRange('2020-10-01', '2020-10-03', Lower::Inclusive, Upper::Inclusive);
        self::assertSame('[2020-10-01,2020-10-04)', (string) $actual);

        // [2020-10-01,2020-10-03) -> [2020-10-01,2020-10-03)
        $actual = new DateRange('2020-10-01', '2020-10-03', Lower::Inclusive, Upper::Exclusive);
        self::assertSame('[2020-10-01,2020-10-03)', (string) $actual);

        // (2020-10-01,2020-10-03] -> [2020-10-02,2020-10-04)
        $actual = new DateRange('2020-10-01', '2020-10-03', Lower::Exclusive, Upper::Inclusive);
        self::assertSame('[2020-10-02,2020-10-04)', (string) $actual);

        // (2020-10-01,2020-10-03) -> [2020-10-02,2020-10-03)
        $actual = new DateRange('2020-10-01', '2020-10-03', Lower::Exclusive, Upper::Exclusive);
        self::assertSame('[2020-10-02,2020-10-03)', (string) $actual);

        // [2020-10-01,) -> [2020-10-01,)
        $actual = new DateRange('2020-10-01', null, Lower::Inclusive, Upper::Exclusive);
        self::assertSame('[2020-10-01,)', (string) $actual);

        // (2020-10-01,] -> [2020-10-02,)
        $actual = new DateRange('2020-10-01', null, Lower::Exclusive, Upper::Inclusive);
        self::assertSame('[2020-10-02,)', (string) $actual);

        // (,2020-10-03] -> (,2020-10-04)
        $actual = new DateRange(null, '2020-10-03', Lower::Exclusive, Upper::Inclusive);
        self::assertSame('(,2020-10-04)', (string) $actual);

        // [,2020-10-03) -> (,2020-10-03)
        $actual = new DateRange(null, '2020-10-03', Lower::Inclusive, Upper::Exclusive);
        self::assertSame('(,2020-10-03)', (string) $actual);

        // [,) -> (,)
        $actual = new DateRange(null, null, Lower::Inclusive, Upper::Exclusive);
        self::assertSame('(,)', (string) $actual);
    }

    public function test_to_array(): void
    {
        // [2020-10-01,2020-10-03] -> [2020-10-01,2020-10-04)
        $actual = (new DateRange('2020-10-01', '2020-10-03', Lower::Inclusive, Upper::Inclusive))->toArray();
        self::assertSame('2020-10-01', $actual[0]?->format('Y-m-d'));
        self::assertSame('2020-10-04', $actual[1]?->format('Y-m-d'));

        // [2020-10-01,2020-10-03) -> [2020-10-01,2020-10-03)
        $actual = (new DateRange('2020-10-01', '2020-10-03', Lower::Inclusive, Upper::Exclusive))->toArray();
        self::assertSame('2020-10-01', $actual[0]?->format('Y-m-d'));
        self::assertSame('2020-10-03', $actual[1]?->format('Y-m-d'));

        // (2020-10-01,2020-10-03] -> [2020-10-02,2020-10-04)
        $actual = (new DateRange('2020-10-01', '2020-10-03', Lower::Exclusive, Upper::Inclusive))->toArray();
        self::assertSame('2020-10-02', $actual[0]?->format('Y-m-d'));
        self::assertSame('2020-10-04', $actual[1]?->format('Y-m-d'));

        // (2020-10-01,2020-10-03) -> [2020-10-02,2020-10-03)
        $actual = (new DateRange('2020-10-01', '2020-10-03', Lower::Exclusive, Upper::Exclusive))->toArray();
        self::assertSame('2020-10-02', $actual[0]?->format('Y-m-d'));
        self::assertSame('2020-10-03', $actual[1]?->format('Y-m-d'));

        // [2020-10-01,) -> [2020-10-01,)
        $actual = (new DateRange('2020-10-01', null, Lower::Inclusive, Upper::Exclusive))->toArray();
        self::assertSame('2020-10-01', $actual[0]?->format('Y-m-d'));
        self::assertNull($actual[1]);

        // (2020-10-01,] -> [2020-10-02,)
        $actual = (new DateRange('2020-10-01', null, Lower::Exclusive, Upper::Inclusive))->toArray();
        self::assertSame('2020-10-02', $actual[0]?->format('Y-m-d'));
        self::assertNull($actual[1]);

        // (,2020-10-03] -> (,2020-10-04)
        $actual = (new DateRange(null, '2020-10-03', Lower::Exclusive, Upper::Inclusive))->toArray();
        self::assertNull($actual[0]);
        self::assertSame('2020-10-04', $actual[1]?->format('Y-m-d'));

        // [,2020-10-03) -> (,2020-10-03)
        $actual = (new DateRange(null, '2020-10-03', Lower::Inclusive, Upper::Exclusive))->toArray();
        self::assertNull($actual[0]);
        self::assertSame('2020-10-03', $actual[1]?->format('Y-m-d'));

        // [,) -> (,)
        $actual = (new DateRange(null, null, Lower::Inclusive, Upper::Exclusive))->toArray();
        self::assertNull($actual[0]);
        self::assertNull($actual[1]);
    }

    public function test_to_inclusive(): void
    {
        // [2020-10-01,2020-10-03] -> [2020-10-01,2020-10-03]
        $actual = (new DateRange('2020-10-01', '2020-10-03', Lower::Inclusive, Upper::Inclusive, canonicalize: false))->toInclusive();
        self::assertSame('[2020-10-01,2020-10-03]', (string) $actual);

        // [2020-10-01,2020-10-03) -> [2020-10-01,2020-10-02]
        $actual = (new DateRange('2020-10-01', '2020-10-03', Lower::Inclusive, Upper::Exclusive, canonicalize: false))->toInclusive();
        self::assertSame('[2020-10-01,2020-10-02]', (string) $actual);

        // (2020-10-01,2020-10-03] -> [2020-10-02,2020-10-03]
        $actual = (new DateRange('2020-10-01', '2020-10-03', Lower::Exclusive, Upper::Inclusive, canonicalize: false))->toInclusive();
        self::assertSame('[2020-10-02,2020-10-03]', (string) $actual);

        // (2020-10-01,2020-10-03) -> [2020-10-02,2020-10-02]
        $actual = (new DateRange('2020-10-01', '2020-10-03', Lower::Exclusive, Upper::Exclusive, canonicalize: false))->toInclusive();
        self::assertSame('[2020-10-02,2020-10-02]', (string) $actual);

        // [2020-10-01,) -> [2020-10-01,)
        $actual = (new DateRange('2020-10-01', null, Lower::Inclusive, Upper::Exclusive, canonicalize: false))->toInclusive();
        self::assertSame('[2020-10-01,)', (string) $actual);

        // (2020-10-01,] -> [2020-10-02,)
        $actual = (new DateRange('2020-10-01', null, Lower::Exclusive, Upper::Inclusive, canonicalize: false))->toInclusive();
        self::assertSame('[2020-10-02,)', (string) $actual);

        // (,2020-10-03] -> (,2020-10-03]
        $actual = (new DateRange(null, '2020-10-03', Lower::Exclusive, Upper::Inclusive, canonicalize: false))->toInclusive();
        self::assertSame('(,2020-10-03]', (string) $actual);

        // [,2020-10-03) -> (,2020-10-02]
        $actual = (new DateRange(null, '2020-10-03', Lower::Inclusive, Upper::Exclusive, canonicalize: false))->toInclusive();
        self::assertSame('(,2020-10-02]', (string) $actual);

        // [,) -> (,)
        $actual = (new DateRange(null, null, Lower::Inclusive, Upper::Exclusive, canonicalize: false))->toInclusive();
        self::assertSame('(,)', (string) $actual);
    }

    public function test_to_exclusive(): void
    {
        // [2020-10-01,2020-10-03] -> (2020-09-30,2020-10-04)
        $actual = (new DateRange('2020-10-01', '2020-10-03', Lower::Inclusive, Upper::Inclusive, canonicalize: false))->toExclusive();
        self::assertSame('(2020-09-30,2020-10-04)', (string) $actual);

        // [2020-10-01,2020-10-03) -> (2020-09-30,2020-10-03)
        $actual = (new DateRange('2020-10-01', '2020-10-03', Lower::Inclusive, Upper::Exclusive, canonicalize: false))->toExclusive();
        self::assertSame('(2020-09-30,2020-10-03)', (string) $actual);

        // (2020-10-01,2020-10-03] -> (2020-10-01,2020-10-04)
        $actual = (new DateRange('2020-10-01', '2020-10-03', Lower::Exclusive, Upper::Inclusive, canonicalize: false))->toExclusive();
        self::assertSame('(2020-10-01,2020-10-04)', (string) $actual);

        // (2020-10-01,2020-10-03) -> (2020-10-01,2020-10-03)
        $actual = (new DateRange('2020-10-01', '2020-10-03', Lower::Exclusive, Upper::Exclusive, canonicalize: false))->toExclusive();
        self::assertSame('(2020-10-01,2020-10-03)', (string) $actual);

        // [2020-10-01,) -> (2020-09-30,)
        $actual = (new DateRange('2020-10-01', null, Lower::Inclusive, Upper::Exclusive, canonicalize: false))->toExclusive();
        self::assertSame('(2020-09-30,)', (string) $actual);

        // (2020-10-01,] -> (2020-10-01,)
        $actual = (new DateRange('2020-10-01', null, Lower::Exclusive, Upper::Inclusive, canonicalize: false))->toExclusive();
        self::assertSame('(2020-10-01,)', (string) $actual);

        // (,2020-10-03] -> (,2020-10-04)
        $actual = (new DateRange(null, '2020-10-03', Lower::Exclusive, Upper::Inclusive, canonicalize: false))->toExclusive();
        self::assertSame('(,2020-10-04)', (string) $actual);

        // [,2020-10-03) -> (,2020-10-03)
        $actual = (new DateRange(null, '2020-10-03', Lower::Inclusive, Upper::Exclusive, canonicalize: false))->toExclusive();
        self::assertSame('(,2020-10-03)', (string) $actual);

        // [,) -> (,)
        $actual = (new DateRange(null, null, Lower::Inclusive, Upper::Exclusive, canonicalize: false))->toExclusive();
        self::assertSame('(,)', (string) $actual);
    }
}
