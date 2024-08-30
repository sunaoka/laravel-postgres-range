<?php

declare(strict_types=1);

namespace Sunaoka\LaravelPostgres\Tests\Eloquent\Casts;

use Illuminate\Support\Facades\Date;
use Sunaoka\LaravelPostgres\Eloquent\Casts\DateRangeCast;
use Sunaoka\LaravelPostgres\Tests\Models\TestModel;
use Sunaoka\LaravelPostgres\Tests\TestCase;
use Sunaoka\LaravelPostgres\Types\DateRange;

class DateRangeCastTest extends TestCase
{
    public function testSet(): void
    {
        $cast = new DateRangeCast;
        $actual = $cast->set(new TestModel, 'date_range', '[2020-10-01,2020-10-03)', []);

        self::assertSame('[2020-10-01,2020-10-03)', $actual['date_range']);
    }

    public function testGet(): void
    {
        $cast = new DateRangeCast;

        $actual = $cast->get(new TestModel, 'date_range', '(2020-10-01,2020-10-03]', []);
        self::assertInstanceOf(DateRange::class, $actual);
        self::assertSame('[2020-10-02,2020-10-04)', (string) $actual);

        $actual = $cast->get(new TestModel, 'date_range', '', []);
        self::assertNull($actual);

        $actual = $cast->get(new TestModel, 'date_range', null, []);
        self::assertNull($actual);

        $actual = $cast->get(new TestModel, 'date_range', 'malformed range literal', []);
        self::assertNull($actual);

        $actual = $cast->get(new TestModel, 'date_range', '[2020-10-01,)', []);
        self::assertSame('[2020-10-01,)', (string) $actual);

        $actual = $cast->get(new TestModel, 'date_range', '[,2020-10-03)', []);
        self::assertSame('(,2020-10-03)', (string) $actual);

        $actual = $cast->get(new TestModel, 'date_range', '[2020-10-01,infinity)', []);
        self::assertSame('[2020-10-01,)', (string) $actual);

        Date::setTestNow('2020-10-01 12:34:56');
        $actual = $cast->get(new TestModel, 'date_range', '(now,infinity]', []);
        self::assertSame('[2020-10-02,)', (string) $actual);
    }
}
