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
    }
}
