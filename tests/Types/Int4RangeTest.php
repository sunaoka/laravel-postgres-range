<?php

declare(strict_types=1);

namespace Sunaoka\LaravelPostgres\Tests\Types;

use Sunaoka\LaravelPostgres\Tests\TestCase;
use Sunaoka\LaravelPostgres\Types\Bounds\Lower;
use Sunaoka\LaravelPostgres\Types\Bounds\Upper;
use Sunaoka\LaravelPostgres\Types\Int4Range;

class Int4RangeTest extends TestCase
{
    public function testToString(): void
    {
        // [0,3] -> [0,4)
        $actual = new Int4Range(0, 3, Lower::Inclusive, Upper::Inclusive);
        self::assertSame('[0,4)', (string) $actual);

        // [0,3) -> [0,3)
        $actual = new Int4Range(0, 3, Lower::Inclusive, Upper::Exclusive);
        self::assertSame('[0,3)', (string) $actual);

        // (0,3] -> [1,4)
        $actual = new Int4Range(0, 3, Lower::Exclusive, Upper::Inclusive);
        self::assertSame('[1,4)', (string) $actual);

        // (0,3) -> [1,3)
        $actual = new Int4Range(0, 3, Lower::Exclusive, Upper::Exclusive);
        self::assertSame('[1,3)', (string) $actual);

        // [0,)  -> [0,)
        $actual = new Int4Range(0, null, Lower::Inclusive, Upper::Exclusive);
        self::assertSame('[0,)', (string) $actual);

        // (0,]  -> [1,)
        $actual = new Int4Range(0, null, Lower::Exclusive, Upper::Inclusive);
        self::assertSame('[1,)', (string) $actual);

        // (,3]  -> (,4)
        $actual = new Int4Range(null, 3, Lower::Exclusive, Upper::Inclusive);
        self::assertSame('(,4)', (string) $actual);

        // [,3)  -> (,3)
        $actual = new Int4Range(null, 3, Lower::Inclusive, Upper::Exclusive);
        self::assertSame('(,3)', (string) $actual);

        // [,)   -> (,)
        $actual = new Int4Range(null, null, Lower::Inclusive, Upper::Exclusive);
        self::assertSame('(,)', (string) $actual);
    }

    public function testToArray(): void
    {
        // [0,3] -> [0,4)
        $actual = (new Int4Range(0, 3, Lower::Inclusive, Upper::Inclusive))->toArray();
        self::assertSame([0, 4], $actual);

        // [0,3) -> [0,3)
        $actual = (new Int4Range(0, 3, Lower::Inclusive, Upper::Exclusive))->toArray();
        self::assertSame([0, 3], $actual);

        // (0,3] -> [1,4)
        $actual = (new Int4Range(0, 3, Lower::Exclusive, Upper::Inclusive))->toArray();
        self::assertSame([1, 4], $actual);

        // (0,3) -> [1,3)
        $actual = (new Int4Range(0, 3, Lower::Exclusive, Upper::Exclusive))->toArray();
        self::assertSame([1, 3], $actual);

        // [0,)  -> [0,)
        $actual = (new Int4Range(0, null, Lower::Inclusive, Upper::Exclusive))->toArray();
        self::assertSame([0, null], $actual);

        // (0,]  -> [1,)
        $actual = (new Int4Range(0, null, Lower::Exclusive, Upper::Inclusive))->toArray();
        self::assertSame([1, null], $actual);

        // (,3]  -> (,4)
        $actual = (new Int4Range(null, 3, Lower::Exclusive, Upper::Inclusive))->toArray();
        self::assertSame([null, 4], $actual);

        // [,3)  -> (,3)
        $actual = (new Int4Range(null, 3, Lower::Inclusive, Upper::Exclusive))->toArray();
        self::assertSame([null, 3], $actual);

        // [,)   -> (,)
        $actual = (new Int4Range(null, null, Lower::Inclusive, Upper::Exclusive))->toArray();
        self::assertSame([null, null], $actual);
    }

    public function testToInclusive(): void
    {
        // [0,3] -> [0,3]
        $actual = (new Int4Range(0, 3, Lower::Inclusive, Upper::Inclusive, canonicalize: false))->toInclusive();
        self::assertSame('[0,3]', (string) $actual);

        // [0,3) -> [0,2]
        $actual = (new Int4Range(0, 3, Lower::Inclusive, Upper::Exclusive, canonicalize: false))->toInclusive();
        self::assertSame('[0,2]', (string) $actual);

        // (0,3] -> [1,3]
        $actual = (new Int4Range(0, 3, Lower::Exclusive, Upper::Inclusive, canonicalize: false))->toInclusive();
        self::assertSame('[1,3]', (string) $actual);

        // (0,3) -> [1,2]
        $actual = (new Int4Range(0, 3, Lower::Exclusive, Upper::Exclusive, canonicalize: false))->toInclusive();
        self::assertSame('[1,2]', (string) $actual);

        // [0,)  -> [0,)
        $actual = (new Int4Range(0, null, Lower::Inclusive, Upper::Exclusive, canonicalize: false))->toInclusive();
        self::assertSame('[0,)', (string) $actual);

        // (0,]  -> [1,)
        $actual = (new Int4Range(0, null, Lower::Exclusive, Upper::Inclusive, canonicalize: false))->toInclusive();
        self::assertSame('[1,)', (string) $actual);

        // (,3]  -> (,3]
        $actual = (new Int4Range(null, 3, Lower::Exclusive, Upper::Inclusive, canonicalize: false))->toInclusive();
        self::assertSame('(,3]', (string) $actual);

        // [,3)  -> (,2]
        $actual = (new Int4Range(null, 3, Lower::Inclusive, Upper::Exclusive, canonicalize: false))->toInclusive();
        self::assertSame('(,2]', (string) $actual);

        // [,)   -> (,)
        $actual = (new Int4Range(null, null, Lower::Inclusive, Upper::Exclusive, canonicalize: false))->toInclusive();
        self::assertSame('(,)', (string) $actual);
    }

    public function testToExclusive(): void
    {
        // [0,3] -> (-1,4)
        $actual = (new Int4Range(0, 3, Lower::Inclusive, Upper::Inclusive, canonicalize: false))->toExclusive();
        self::assertSame('(-1,4)', (string) $actual);

        // [0,3) -> (-1,3)
        $actual = (new Int4Range(0, 3, Lower::Inclusive, Upper::Exclusive, canonicalize: false))->toExclusive();
        self::assertSame('(-1,3)', (string) $actual);

        // (0,3] -> (0,4)
        $actual = (new Int4Range(0, 3, Lower::Exclusive, Upper::Inclusive, canonicalize: false))->toExclusive();
        self::assertSame('(0,4)', (string) $actual);

        // (0,3) -> (0,3)
        $actual = (new Int4Range(0, 3, Lower::Exclusive, Upper::Exclusive, canonicalize: false))->toExclusive();
        self::assertSame('(0,3)', (string) $actual);

        // [0,)  -> (-1,)
        $actual = (new Int4Range(0, null, Lower::Inclusive, Upper::Exclusive, canonicalize: false))->toExclusive();
        self::assertSame('(-1,)', (string) $actual);

        // (0,]  -> (0,)
        $actual = (new Int4Range(0, null, Lower::Exclusive, Upper::Inclusive, canonicalize: false))->toExclusive();
        self::assertSame('(0,)', (string) $actual);

        // (,3]  -> (,4)
        $actual = (new Int4Range(null, 3, Lower::Exclusive, Upper::Inclusive, canonicalize: false))->toExclusive();
        self::assertSame('(,4)', (string) $actual);

        // [,3)  -> (,3)
        $actual = (new Int4Range(null, 3, Lower::Inclusive, Upper::Exclusive, canonicalize: false))->toExclusive();
        self::assertSame('(,3)', (string) $actual);

        // [,)   -> (,)
        $actual = (new Int4Range(null, null, Lower::Inclusive, Upper::Exclusive, canonicalize: false))->toExclusive();
        self::assertSame('(,)', (string) $actual);
    }
}
