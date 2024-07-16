<?php

declare(strict_types=1);

namespace Sunaoka\LaravelPostgres\Tests\Types;

use Sunaoka\LaravelPostgres\Tests\TestCase;
use Sunaoka\LaravelPostgres\Types\Bounds\Lower;
use Sunaoka\LaravelPostgres\Types\Bounds\Upper;
use Sunaoka\LaravelPostgres\Types\NumRange;

class NumRangeTest extends TestCase
{
    public function testToString(): void
    {
        // [0.1,3.1] -> [0.1,3.1]
        $actual = new NumRange(0.1, 3.1, Lower::Inclusive, Upper::Inclusive);
        self::assertSame('[0.1,3.1]', (string) $actual);

        // [0.1,3.1) -> [0.1,3.1)
        $actual = new NumRange(0.1, 3.1, Lower::Inclusive, Upper::Exclusive);
        self::assertSame('[0.1,3.1)', (string) $actual);

        // (0.1,3.1] -> (0.1,3.1]
        $actual = new NumRange(0.1, 3.1, Lower::Exclusive, Upper::Inclusive);
        self::assertSame('(0.1,3.1]', (string) $actual);

        // (0.1,3.1) -> (0.1,3.1)
        $actual = new NumRange(0.1, 3.1, Lower::Exclusive, Upper::Exclusive);
        self::assertSame('(0.1,3.1)', (string) $actual);

        // [0.1,) -> [0.1,)
        $actual = new NumRange(0.1, null, Lower::Inclusive, Upper::Exclusive);
        self::assertSame('[0.1,)', (string) $actual);

        // (0.1,] -> (0.1,)
        $actual = new NumRange(0.1, null, Lower::Exclusive, Upper::Inclusive);
        self::assertSame('(0.1,)', (string) $actual);

        // (,3.1] -> (,3.1]
        $actual = new NumRange(null, 3.1, Lower::Exclusive, Upper::Inclusive);
        self::assertSame('(,3.1]', (string) $actual);

        // [,3.1) -> (,3.1)
        $actual = new NumRange(null, 3.1, Lower::Inclusive, Upper::Exclusive);
        self::assertSame('(,3.1)', (string) $actual);

        // [,) -> (,)
        $actual = new NumRange(null, null, Lower::Inclusive, Upper::Exclusive);
        self::assertSame('(,)', (string) $actual);
    }

    public function testToArray(): void
    {
        // [0.1,3.1] -> [0.1,3.1]
        $actual = (new NumRange(0.1, 3.1, Lower::Inclusive, Upper::Inclusive))->toArray();
        self::assertSame([0.1, 3.1], $actual);

        // [0.1,3.1) -> [0.1,3.1)
        $actual = (new NumRange(0.1, 3.1, Lower::Inclusive, Upper::Exclusive))->toArray();
        self::assertSame([0.1, 3.1], $actual);

        // (0.1,3.1] -> (0.1,3.1]
        $actual = (new NumRange(0.1, 3.1, Lower::Exclusive, Upper::Inclusive))->toArray();
        self::assertSame([0.1, 3.1], $actual);

        // (0.1,3.1) -> (0.1,3.1)
        $actual = (new NumRange(0.1, 3.1, Lower::Exclusive, Upper::Exclusive))->toArray();
        self::assertSame([0.1, 3.1], $actual);

        // [0.1,) -> [0.1,)
        $actual = (new NumRange(0.1, null, Lower::Inclusive, Upper::Exclusive))->toArray();
        self::assertSame([0.1, null], $actual);

        // (0.1,] -> (0.1,)
        $actual = (new NumRange(0.1, null, Lower::Exclusive, Upper::Inclusive))->toArray();
        self::assertSame([0.1, null], $actual);

        // (,3.1] -> (,3.1]
        $actual = (new NumRange(null, 3.1, Lower::Exclusive, Upper::Inclusive))->toArray();
        self::assertSame([null, 3.1], $actual);

        // [,3.1) -> (,3.1)
        $actual = (new NumRange(null, 3.1, Lower::Inclusive, Upper::Exclusive))->toArray();
        self::assertSame([null, 3.1], $actual);

        // [,) -> (,)
        $actual = (new NumRange(null, null, Lower::Inclusive, Upper::Exclusive))->toArray();
        self::assertSame([null, null], $actual);
    }
}
