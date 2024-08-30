<?php

declare(strict_types=1);

namespace Sunaoka\LaravelPostgres\Tests\Eloquent\Casts;

use Illuminate\Support\Facades\Date;
use Sunaoka\LaravelPostgres\Eloquent\Casts\TsTzRangeCast;
use Sunaoka\LaravelPostgres\Tests\Models\TestModel;
use Sunaoka\LaravelPostgres\Tests\TestCase;
use Sunaoka\LaravelPostgres\Types\TsTzRange;

class TsTzRangeCastTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        date_default_timezone_set('UTC');
    }

    public function testSet(): void
    {
        $cast = new TsTzRangeCast;
        $actual = $cast->set(new TestModel, 'ts_tz_range', '[2020-10-01 00:00:00,2020-10-01 23:59:59)', []);

        self::assertSame('[2020-10-01 00:00:00,2020-10-01 23:59:59)', $actual['ts_tz_range']);
    }

    public function testGet(): void
    {
        $cast = new TsTzRangeCast;

        $actual = $cast->get(new TestModel, 'ts_tz_range', '(2020-10-01 00:00:00,2020-10-01 23:59:59]', []);
        self::assertInstanceOf(TsTzRange::class, $actual);
        self::assertSame('("2020-10-01 00:00:00+00:00","2020-10-01 23:59:59+00:00"]', (string) $actual);

        $actual = $cast->get(new TestModel, 'ts_tz_range', '', []);
        self::assertNull($actual);

        $actual = $cast->get(new TestModel, 'ts_tz_range', null, []);
        self::assertNull($actual);

        $actual = $cast->get(new TestModel, 'ts_tz_range', 'malformed range literal', []);
        self::assertNull($actual);

        $actual = $cast->get(new TestModel, 'ts_tz_range', '[2020-10-01 00:00:00,)', []);
        self::assertSame('["2020-10-01 00:00:00+00:00",)', (string) $actual);

        $actual = $cast->get(new TestModel, 'ts_tz_range', '[,2020-10-01 23:59:59)', []);
        self::assertSame('(,"2020-10-01 23:59:59+00:00")', (string) $actual);

        $actual = $cast->get(new TestModel, 'ts_tz_range', '[2020-10-01 00:00:00,infinity)', []);
        self::assertSame('["2020-10-01 00:00:00+00:00",)', (string) $actual);

        $actual = $cast->get(new TestModel, 'ts_tz_range', '[-infinity,2020-10-01 23:59:59)', []);
        self::assertSame('(,"2020-10-01 23:59:59+00:00")', (string) $actual);

        Date::setTestNow('2020-10-01 12:34:56+00:00');
        $actual = $cast->get(new TestModel, 'ts_tz_range', '(now,infinity]', []);
        self::assertSame('("2020-10-01 12:34:56+00:00",)', (string) $actual);
    }
}
