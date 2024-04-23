<?php

declare(strict_types=1);

namespace Sunaoka\LaravelPostgres\Tests\Eloquent\Casts;

use Sunaoka\LaravelPostgres\Eloquent\Casts\Int4RangeCast;
use Sunaoka\LaravelPostgres\Tests\Models\TestModel;
use Sunaoka\LaravelPostgres\Tests\TestCase;
use Sunaoka\LaravelPostgres\Types\Int4Range;

class Int4RangeCastTest extends TestCase
{
    public function testSet(): void
    {
        $cast = new Int4RangeCast();
        $actual = $cast->set(new TestModel(), 'int4_range', '[1,3)', []);

        self::assertSame('[1,3)', $actual['int4_range']);
    }

    public function testGet(): void
    {
        $cast = new Int4RangeCast();

        $actual = $cast->get(new TestModel(), 'int4_range', '[1,3)', []);
        self::assertInstanceOf(Int4Range::class, $actual);
        self::assertSame('[1,3)', (string) $actual);

        $actual = $cast->get(new TestModel(), 'int4_range', '', []);
        self::assertNull($actual);

        $actual = $cast->get(new TestModel(), 'int4_range', null, []);
        self::assertNull($actual);

        $actual = $cast->get(new TestModel(), 'int4_range', 'malformed range literal', []);
        self::assertNull($actual);

        $actual = $cast->get(new TestModel(), 'int4_range', '[1,)', []);
        self::assertSame('[1,)', (string) $actual);

        $actual = $cast->get(new TestModel(), 'int4_range', '[,10)', []);
        self::assertSame('[,10)', (string) $actual);
    }
}
