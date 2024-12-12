<?php

declare(strict_types=1);

namespace Sunaoka\LaravelPostgres\Tests\Models;

use Sunaoka\LaravelPostgres\Tests\TestCase;
use Sunaoka\LaravelPostgres\Types\Bounds\Lower;
use Sunaoka\LaravelPostgres\Types\Bounds\Upper;
use Sunaoka\LaravelPostgres\Types\DateRange;
use Sunaoka\LaravelPostgres\Types\Int4Range;
use Sunaoka\LaravelPostgres\Types\Int8Range;
use Sunaoka\LaravelPostgres\Types\NumRange;
use Sunaoka\LaravelPostgres\Types\TsRange;
use Sunaoka\LaravelPostgres\Types\TsTzRange;

class ModelTest extends TestCase
{
    public function test_cast(): void
    {
        $model = new TestModel;

        $model->ts_range = new TsRange('2020-10-01 00:00:00', '2020-10-01 23:59:59', Lower::Inclusive, Upper::Exclusive);
        $model->ts_tz_range = new TsTzRange('2020-10-01 00:00:00', '2020-10-01 23:59:59', Lower::Inclusive, Upper::Exclusive);
        $model->date_range = new DateRange('2020-10-01', '2020-10-02', Lower::Inclusive, Upper::Exclusive);
        $model->int4_range = new Int4Range(1, 3, Lower::Inclusive, Upper::Exclusive);
        $model->int8_range = new Int8Range(1000, 3000, Lower::Inclusive, Upper::Exclusive);
        $model->num_range = new NumRange(0.1, 0.3, Lower::Inclusive, Upper::Exclusive);

        self::assertInstanceOf(TsRange::class, $model->ts_range);
        self::assertSame('["2020-10-01 00:00:00","2020-10-01 23:59:59")', (string) $model->ts_range);

        self::assertInstanceOf(TsTzRange::class, $model->ts_tz_range);
        self::assertSame('["2020-10-01 00:00:00+00:00","2020-10-01 23:59:59+00:00")', (string) $model->ts_tz_range);

        self::assertInstanceOf(DateRange::class, $model->date_range);
        self::assertSame('[2020-10-01,2020-10-02)', (string) $model->date_range);

        self::assertInstanceOf(Int4Range::class, $model->int4_range);
        self::assertSame('[1,3)', (string) $model->int4_range);

        self::assertInstanceOf(Int8Range::class, $model->int8_range);
        self::assertSame('[1000,3000)', (string) $model->int8_range);

        self::assertInstanceOf(NumRange::class, $model->num_range);
        self::assertSame('[0.1,0.3)', (string) $model->num_range);
    }
}
