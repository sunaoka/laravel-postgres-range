<?php

declare(strict_types=1);

namespace Sunaoka\LaravelPostgres\Tests\Types;

use Sunaoka\LaravelPostgres\Tests\TestCase;
use Sunaoka\LaravelPostgres\Types\Bounds\Lower;
use Sunaoka\LaravelPostgres\Types\Bounds\Upper;
use Sunaoka\LaravelPostgres\Types\TsTzRange;

class TsTzRangeTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        date_default_timezone_set('Asia/Tokyo');
    }

    public function test_to_string(): void
    {
        // [2020-10-01 00:00:00+09:00,2020-10-01 23:59:59+09:00] -> ["2020-09-30 15:00:00+00:00","2020-10-01 14:59:59+00:00"]
        $actual = new TsTzRange('2020-10-01 00:00:00+09:00', '2020-10-01 23:59:59+09:00', Lower::Inclusive, Upper::Inclusive, timezone: 'UTC');
        self::assertSame('["2020-09-30 15:00:00+00:00","2020-10-01 14:59:59+00:00"]', (string) $actual);

        // [2020-10-01 00:00:00+09:00,2020-10-01 23:59:59+09:00) -> ["2020-09-30 15:00:00+00:00","2020-10-01 14:59:59+00:00")
        $actual = new TsTzRange('2020-10-01 00:00:00+09:00', '2020-10-01 23:59:59+09:00', Lower::Inclusive, Upper::Exclusive, timezone: 'UTC');
        self::assertSame('["2020-09-30 15:00:00+00:00","2020-10-01 14:59:59+00:00")', (string) $actual);

        // (2020-10-01 00:00:00+09:00,2020-10-01 23:59:59+09:00] -> ("2020-09-30 15:00:00+00:00","2020-10-01 14:59:59+00:00"]
        $actual = new TsTzRange('2020-10-01 00:00:00+09:00', '2020-10-01 23:59:59+09:00', Lower::Exclusive, Upper::Inclusive, timezone: 'UTC');
        self::assertSame('("2020-09-30 15:00:00+00:00","2020-10-01 14:59:59+00:00"]', (string) $actual);

        // (2020-10-01 00:00:00+09:00,2020-10-01 23:59:59+09:00) -> ("2020-09-30 15:00:00+00:00","2020-10-01 14:59:59+00:00")
        $actual = new TsTzRange('2020-10-01 00:00:00+09:00', '2020-10-01 23:59:59+09:00', Lower::Exclusive, Upper::Exclusive, timezone: 'UTC');
        self::assertSame('("2020-09-30 15:00:00+00:00","2020-10-01 14:59:59+00:00")', (string) $actual);

        // [2020-10-01 00:00:00+09:00,) -> ["2020-09-30 15:00:00+00:00",)
        $actual = new TsTzRange('2020-10-01 00:00:00+09:00', null, Lower::Inclusive, Upper::Exclusive, timezone: 'UTC');
        self::assertSame('["2020-09-30 15:00:00+00:00",)', (string) $actual);

        // (2020-10-01 00:00:00+09:00,] -> ("2020-09-30 15:00:00+00:00",)
        $actual = new TsTzRange('2020-10-01 00:00:00+09:00', null, Lower::Exclusive, Upper::Inclusive, timezone: 'UTC');
        self::assertSame('("2020-09-30 15:00:00+00:00",)', (string) $actual);

        // (,2020-10-01 23:59:59+09:00] -> (,"2020-10-01 14:59:59+00:00"]
        $actual = new TsTzRange(null, '2020-10-01 23:59:59+09:00', Lower::Exclusive, Upper::Inclusive, timezone: 'UTC');
        self::assertSame('(,"2020-10-01 14:59:59+00:00"]', (string) $actual);

        // [,2020-10-01 23:59:59+09:00) -> (,"2020-10-01 14:59:59+00:00")
        $actual = new TsTzRange(null, '2020-10-01 23:59:59+09:00', Lower::Inclusive, Upper::Exclusive, timezone: 'UTC');
        self::assertSame('(,"2020-10-01 14:59:59+00:00")', (string) $actual);

        // [,) -> (,)
        $actual = new TsTzRange(null, null, Lower::Inclusive, Upper::Exclusive, timezone: 'UTC');
        self::assertSame('(,)', (string) $actual);
    }

    public function test_to_array(): void
    {
        // [2020-10-01 00:00:00+09:00,2020-10-01 23:59:59+09:00] -> ["2020-09-30 15:00:00+00:00","2020-10-01 14:59:59+00:00"]
        $actual = (new TsTzRange('2020-10-01 00:00:00+09:00', '2020-10-01 23:59:59+09:00', Lower::Inclusive, Upper::Inclusive, timezone: 'UTC'))->toArray();
        self::assertSame('2020-09-30 15:00:00+00:00', $actual[0]?->format('Y-m-d H:i:sP'));
        self::assertSame('2020-10-01 14:59:59+00:00', $actual[1]?->format('Y-m-d H:i:sP'));

        // [2020-10-01 00:00:00+09:00,2020-10-01 23:59:59+09:00) -> ["2020-09-30 15:00:00+00:00","2020-10-01 14:59:59+00:00")
        $actual = (new TsTzRange('2020-10-01 00:00:00+09:00', '2020-10-01 23:59:59+09:00', Lower::Inclusive, Upper::Exclusive, timezone: 'UTC'))->toArray();
        self::assertSame('2020-09-30 15:00:00+00:00', $actual[0]?->format('Y-m-d H:i:sP'));
        self::assertSame('2020-10-01 14:59:59+00:00', $actual[1]?->format('Y-m-d H:i:sP'));

        // (2020-10-01 00:00:00+09:00,2020-10-01 23:59:59+09:00] -> ("2020-09-30 15:00:00+00:00","2020-10-01 14:59:59+00:00"]
        $actual = (new TsTzRange('2020-10-01 00:00:00+09:00', '2020-10-01 23:59:59+09:00', Lower::Exclusive, Upper::Inclusive, timezone: 'UTC'))->toArray();
        self::assertSame('2020-09-30 15:00:00+00:00', $actual[0]?->format('Y-m-d H:i:sP'));
        self::assertSame('2020-10-01 14:59:59+00:00', $actual[1]?->format('Y-m-d H:i:sP'));

        // (2020-10-01 00:00:00+09:00,2020-10-01 23:59:59+09:00) -> ("2020-09-30 15:00:00+00:00","2020-10-01 14:59:59+00:00")
        $actual = (new TsTzRange('2020-10-01 00:00:00+09:00', '2020-10-01 23:59:59+09:00', Lower::Exclusive, Upper::Exclusive, timezone: 'UTC'))->toArray();
        self::assertSame('2020-09-30 15:00:00+00:00', $actual[0]?->format('Y-m-d H:i:sP'));
        self::assertSame('2020-10-01 14:59:59+00:00', $actual[1]?->format('Y-m-d H:i:sP'));

        // [2020-10-01 00:00:00+09:00,) -> ["2020-09-30 15:00:00+00:00",)
        $actual = (new TsTzRange('2020-10-01 00:00:00+09:00', null, Lower::Inclusive, Upper::Exclusive, timezone: 'UTC'))->toArray();
        self::assertSame('2020-09-30 15:00:00+00:00', $actual[0]?->format('Y-m-d H:i:sP'));
        self::assertNull($actual[1]);

        // (2020-10-01 00:00:00+09:00,] -> ("2020-09-30 15:00:00+00:00",)
        $actual = (new TsTzRange('2020-10-01 00:00:00+09:00', null, Lower::Exclusive, Upper::Inclusive, timezone: 'UTC'))->toArray();
        self::assertSame('2020-09-30 15:00:00+00:00', $actual[0]?->format('Y-m-d H:i:sP'));
        self::assertNull($actual[1]);

        // (,2020-10-01 23:59:59+09:00] -> (,"2020-10-01 14:59:59+00:00"]
        $actual = (new TsTzRange(null, '2020-10-01 23:59:59+09:00', Lower::Exclusive, Upper::Inclusive, timezone: 'UTC'))->toArray();
        self::assertNull($actual[0]);
        self::assertSame('2020-10-01 14:59:59+00:00', $actual[1]?->format('Y-m-d H:i:sP'));

        // [,2020-10-01 23:59:59+09:00) -> (,"2020-10-01 14:59:59+00:00")
        $actual = (new TsTzRange(null, '2020-10-01 23:59:59+09:00', Lower::Inclusive, Upper::Exclusive, timezone: 'UTC'))->toArray();
        self::assertNull($actual[0]);
        self::assertSame('2020-10-01 14:59:59+00:00', $actual[1]?->format('Y-m-d H:i:sP'));

        // [,) -> (,)
        $actual = (new TsTzRange(null, null, Lower::Inclusive, Upper::Exclusive, timezone: 'UTC'))->toArray();
        self::assertNull($actual[0]);
        self::assertNull($actual[1]);
    }
}
