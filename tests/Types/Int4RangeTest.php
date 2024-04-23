<?php

declare(strict_types=1);

namespace Sunaoka\LaravelPostgres\Tests\Types;

use Sunaoka\LaravelPostgres\Tests\TestCase;
use Sunaoka\LaravelPostgres\Types\Bounds\Lower;
use Sunaoka\LaravelPostgres\Types\Bounds\Upper;
use Sunaoka\LaravelPostgres\Types\Int4Range;

class Int4RangeTest extends TestCase
{
    public function test(): void
    {
        $int4Range = new Int4Range(1, 3, Lower::Exclusive, Upper::Inclusive);

        self::assertNotNull($int4Range->from());
        self::assertNotNull($int4Range->to());

        self::assertSame(1, $int4Range->from());
        self::assertSame(3, $int4Range->to());

        self::assertSame(Lower::Exclusive, $int4Range->bounds()->lower());
        self::assertSame(Upper::Inclusive, $int4Range->bounds()->upper());

        self::assertSame('(1,3]', (string) $int4Range);

        $int4Range = new Int4Range(0, 3, Lower::Exclusive, Upper::Inclusive);

        self::assertNotNull($int4Range->from());
        self::assertNotNull($int4Range->to());

        self::assertSame(0, $int4Range->from());
        self::assertSame(3, $int4Range->to());

        self::assertSame(Lower::Exclusive, $int4Range->bounds()->lower());
        self::assertSame(Upper::Inclusive, $int4Range->bounds()->upper());

        self::assertSame('(0,3]', (string) $int4Range);
    }

    public function testToInclusive(): void
    {
        // [1,3) => [1,2]
        $actual = (new Int4Range(1, 3, Lower::Inclusive, Upper::Exclusive))->toInclusive();
        self::assertInstanceOf(Int4Range::class, $actual);
        self::assertSame('[1,2]', (string) $actual);

        // [1,2] => [1,2]
        $actual = (new Int4Range(1, 2, Lower::Inclusive, Upper::Inclusive))->toInclusive();
        self::assertInstanceOf(Int4Range::class, $actual);
        self::assertSame('[1,2]', (string) $actual);

        // (0,2] => [1,2]
        $actual = (new Int4Range(0, 2, Lower::Exclusive, Upper::Inclusive))->toInclusive();
        self::assertInstanceOf(Int4Range::class, $actual);
        self::assertSame('[1,2]', (string) $actual);

        // (0,3) => [1,2]
        $actual = (new Int4Range(0, 3, Lower::Exclusive, Upper::Exclusive))->toInclusive();
        self::assertInstanceOf(Int4Range::class, $actual);
        self::assertSame('[1,2]', (string) $actual);

        // (,2] => [,2]
        $actual = (new Int4Range(null, 2, Lower::Exclusive, Upper::Inclusive))->toInclusive();
        self::assertInstanceOf(Int4Range::class, $actual);
        self::assertSame('[,2]', (string) $actual);

        // [0,) => [0,]
        $actual = (new Int4Range(0, null, Lower::Inclusive, Upper::Exclusive))->toInclusive();
        self::assertInstanceOf(Int4Range::class, $actual);
        self::assertSame('[0,]', (string) $actual);
    }

    public function testToExclusive(): void
    {
        // [1,3) => (0,3)
        $actual = (new Int4Range(1, 3, Lower::Inclusive, Upper::Exclusive))->toExclusive();
        self::assertInstanceOf(Int4Range::class, $actual);
        self::assertSame('(0,3)', (string) $actual);

        // [1,2] => (0,3)
        $actual = (new Int4Range(1, 2, Lower::Inclusive, Upper::Inclusive))->toExclusive();
        self::assertInstanceOf(Int4Range::class, $actual);
        self::assertSame('(0,3)', (string) $actual);

        // (0,2] => (0,3)
        $actual = (new Int4Range(0, 2, Lower::Exclusive, Upper::Inclusive))->toExclusive();
        self::assertInstanceOf(Int4Range::class, $actual);
        self::assertSame('(0,3)', (string) $actual);

        // (0,3) => (0,3)
        $actual = (new Int4Range(0, 3, Lower::Exclusive, Upper::Exclusive))->toExclusive();
        self::assertInstanceOf(Int4Range::class, $actual);
        self::assertSame('(0,3)', (string) $actual);

        // [,2) => [,2)
        $actual = (new Int4Range(null, 2, Lower::Inclusive, Upper::Exclusive))->toExclusive();
        self::assertInstanceOf(Int4Range::class, $actual);
        self::assertSame('(,2)', (string) $actual);

        // (0,] => (0,)
        $actual = (new Int4Range(0, null, Lower::Exclusive, Upper::Inclusive))->toExclusive();
        self::assertInstanceOf(Int4Range::class, $actual);
        self::assertSame('(0,)', (string) $actual);
    }
}
