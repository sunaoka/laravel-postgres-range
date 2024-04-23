<?php

declare(strict_types=1);

namespace Sunaoka\LaravelPostgres\Tests\Models;

use Sunaoka\LaravelPostgres\Tests\TestCase;
use Sunaoka\LaravelPostgres\Types\Int4Range;
use Sunaoka\LaravelPostgres\Types\TsRange;

class ModelTest extends TestCase
{
    public function testCast(): void
    {
        $model = new TestModel([
            'ts_range' => '[2020-10-01 00:00:00,2020-10-01 23:59:59)',
            'int4_range' => '[1,3)',
        ]);

        self::assertInstanceOf(TsRange::class, $model->ts_range);
        self::assertSame('[2020-10-01 00:00:00,2020-10-01 23:59:59)', (string) $model->ts_range);

        self::assertInstanceOf(Int4Range::class, $model->int4_range);
        self::assertSame('[1,3)', (string) $model->int4_range);
    }
}
