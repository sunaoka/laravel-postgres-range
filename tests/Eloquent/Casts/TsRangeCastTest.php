<?php

declare(strict_types=1);

namespace Sunaoka\LaravelPostgres\Tests\Eloquent\Casts;

use Sunaoka\LaravelPostgres\Eloquent\Casts\TsRangeCast;
use Sunaoka\LaravelPostgres\Tests\Models\TestModel;
use Sunaoka\LaravelPostgres\Tests\TestCase;

class TsRangeCastTest extends TestCase
{
    public function testSet(): void
    {
        $cast = new TsRangeCast();
        $actual = $cast->set(new TestModel(), 'ts_range', '[2020-10-01 00:00:00,2020-10-01 23:59:59)', []);

        self::assertSame('[2020-10-01 00:00:00,2020-10-01 23:59:59)', $actual['ts_range']);
    }

    public function testGet(): void
    {
        $cast = new TsRangeCast();
        $actual = $cast->get(new TestModel(), 'ts_range', '[2020-10-01 00:00:00,2020-10-01 23:59:59)', []);

        self::assertSame('[2020-10-01 00:00:00,2020-10-01 23:59:59)', (string) $actual);

        $actual = $cast->get(new TestModel(), 'ts_range', '', []);

        self::assertNull($actual);
    }
}
