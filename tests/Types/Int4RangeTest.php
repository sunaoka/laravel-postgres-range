<?php

declare(strict_types=1);

namespace Sunaoka\LaravelPostgres\Tests\Types;

use Sunaoka\LaravelPostgres\Tests\TestCase;
use Sunaoka\LaravelPostgres\Types\Int4Range;

class Int4RangeTest extends TestCase
{
    public function test(): void
    {
        $int4Range = new Int4Range(1, 3, '(', ']');

        self::assertNotNull($int4Range->from());
        self::assertNotNull($int4Range->to());

        self::assertSame(1, $int4Range->from());
        self::assertSame(3, $int4Range->to());

        self::assertSame('(1,3]', (string) $int4Range);
    }
}
