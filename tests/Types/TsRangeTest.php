<?php

declare(strict_types=1);

namespace Sunaoka\LaravelPostgres\Tests\Types;

use Sunaoka\LaravelPostgres\Tests\TestCase;
use Sunaoka\LaravelPostgres\Types\Bounds;
use Sunaoka\LaravelPostgres\Types\TsRange;

class TsRangeTest extends TestCase
{
    public function test(): void
    {
        $tsRange = new TsRange('2020-10-01 00:00:00', '2020-10-01 23:59:59', Bounds::EXCLUSIVE_LOWER, Bounds::INCLUSIVE_UPPER);

        self::assertNotNull($tsRange->from());
        self::assertNotNull($tsRange->to());

        self::assertSame('2020-10-01 00:00:00', $tsRange->from()->format('Y-m-d H:i:s'));
        self::assertSame('2020-10-01 23:59:59', $tsRange->to()->format('Y-m-d H:i:s'));

        self::assertSame(Bounds::EXCLUSIVE_LOWER, $tsRange->bounds()->lower());
        self::assertSame(Bounds::INCLUSIVE_UPPER, $tsRange->bounds()->upper());

        self::assertSame('(2020-10-01 00:00:00,2020-10-01 23:59:59]', (string) $tsRange);
    }
}
