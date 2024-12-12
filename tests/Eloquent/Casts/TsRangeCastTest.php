<?php

declare(strict_types=1);

namespace Sunaoka\LaravelPostgres\Tests\Eloquent\Casts;

use Illuminate\Support\Facades\Date;
use Sunaoka\LaravelPostgres\Eloquent\Casts\TsRangeCast;
use Sunaoka\LaravelPostgres\Tests\Models\TestModel;
use Sunaoka\LaravelPostgres\Tests\TestCase;
use Sunaoka\LaravelPostgres\Types\TsRange;

class TsRangeCastTest extends TestCase
{
    public function test_set(): void
    {
        $cast = new TsRangeCast;
        $actual = $cast->set(new TestModel, 'ts_range', '[2020-10-01 00:00:00,2020-10-01 23:59:59)', []);

        self::assertSame('[2020-10-01 00:00:00,2020-10-01 23:59:59)', $actual['ts_range']);
    }

    public function test_get(): void
    {
        $cast = new TsRangeCast;

        $actual = $cast->get(new TestModel, 'ts_range', '(2020-10-01 00:00:00,2020-10-01 23:59:59]', []);
        self::assertInstanceOf(TsRange::class, $actual);
        self::assertSame('("2020-10-01 00:00:00","2020-10-01 23:59:59"]', (string) $actual);

        $actual = $cast->get(new TestModel, 'ts_range', '', []);
        self::assertNull($actual);

        $actual = $cast->get(new TestModel, 'ts_range', null, []);
        self::assertNull($actual);

        $actual = $cast->get(new TestModel, 'ts_range', 'malformed range literal', []);
        self::assertNull($actual);

        $actual = $cast->get(new TestModel, 'ts_range', '[2020-10-01 00:00:00,)', []);
        self::assertSame('["2020-10-01 00:00:00",)', (string) $actual);

        $actual = $cast->get(new TestModel, 'ts_range', '[,2020-10-01 23:59:59)', []);
        self::assertSame('(,"2020-10-01 23:59:59")', (string) $actual);

        $actual = $cast->get(new TestModel, 'ts_range', '[2020-10-01 00:00:00,infinity)', []);
        self::assertSame('["2020-10-01 00:00:00",)', (string) $actual);

        Date::setTestNow('2020-10-01 12:34:56');
        $actual = $cast->get(new TestModel, 'ts_range', '(now,infinity]', []);
        self::assertSame('("2020-10-01 12:34:56",)', (string) $actual);
    }
}
