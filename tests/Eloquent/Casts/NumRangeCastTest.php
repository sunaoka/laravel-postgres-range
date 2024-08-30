<?php

declare(strict_types=1);

namespace Sunaoka\LaravelPostgres\Tests\Eloquent\Casts;

use Sunaoka\LaravelPostgres\Eloquent\Casts\NumRangeCast;
use Sunaoka\LaravelPostgres\Tests\Models\TestModel;
use Sunaoka\LaravelPostgres\Tests\TestCase;
use Sunaoka\LaravelPostgres\Types\NumRange;

class NumRangeCastTest extends TestCase
{
    public function testSet(): void
    {
        $cast = new NumRangeCast;
        $actual = $cast->set(new TestModel, 'num_range', '[0.1,0.3)', []);

        self::assertSame('[0.1,0.3)', $actual['num_range']);
    }

    public function testGet(): void
    {
        $cast = new NumRangeCast;

        $actual = $cast->get(new TestModel, 'num_range', '(0.1,0.3]', []);
        self::assertInstanceOf(NumRange::class, $actual);
        self::assertSame('(0.1,0.3]', (string) $actual);

        $actual = $cast->get(new TestModel, 'num_range', '', []);
        self::assertNull($actual);

        $actual = $cast->get(new TestModel, 'num_range', null, []);
        self::assertNull($actual);

        $actual = $cast->get(new TestModel, 'num_range', 'malformed range literal', []);
        self::assertNull($actual);

        $actual = $cast->get(new TestModel, 'num_range', '[0.1,)', []);
        self::assertSame('[0.1,)', (string) $actual);

        $actual = $cast->get(new TestModel, 'num_range', '[,10.1)', []);
        self::assertSame('(,10.1)', (string) $actual);
    }
}
