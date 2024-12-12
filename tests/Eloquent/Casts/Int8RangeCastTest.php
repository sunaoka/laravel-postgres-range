<?php

declare(strict_types=1);

namespace Sunaoka\LaravelPostgres\Tests\Eloquent\Casts;

use Sunaoka\LaravelPostgres\Eloquent\Casts\Int8RangeCast;
use Sunaoka\LaravelPostgres\Tests\Models\TestModel;
use Sunaoka\LaravelPostgres\Tests\TestCase;
use Sunaoka\LaravelPostgres\Types\Int8Range;

class Int8RangeCastTest extends TestCase
{
    public function test_set(): void
    {
        $cast = new Int8RangeCast;
        $actual = $cast->set(new TestModel, 'int8_range', '[1,3)', []);

        self::assertSame('[1,3)', $actual['int8_range']);
    }

    public function test_get(): void
    {
        $cast = new Int8RangeCast;

        $actual = $cast->get(new TestModel, 'int8_range', '(1,3]', []);
        self::assertInstanceOf(Int8Range::class, $actual);
        self::assertSame('[2,4)', (string) $actual);

        $actual = $cast->get(new TestModel, 'int8_range', '', []);
        self::assertNull($actual);

        $actual = $cast->get(new TestModel, 'int8_range', null, []);
        self::assertNull($actual);

        $actual = $cast->get(new TestModel, 'int8_range', 'malformed range literal', []);
        self::assertNull($actual);

        $actual = $cast->get(new TestModel, 'int8_range', '[1,)', []);
        self::assertSame('[1,)', (string) $actual);

        $actual = $cast->get(new TestModel, 'int8_range', '[,10)', []);
        self::assertSame('(,10)', (string) $actual);
    }
}
