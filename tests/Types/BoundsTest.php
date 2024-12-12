<?php

declare(strict_types=1);

namespace Sunaoka\LaravelPostgres\Tests\Types;

use Sunaoka\LaravelPostgres\Tests\TestCase;
use Sunaoka\LaravelPostgres\Types\Bounds;
use Sunaoka\LaravelPostgres\Types\Bounds\Lower;
use Sunaoka\LaravelPostgres\Types\Bounds\Upper;

class BoundsTest extends TestCase
{
    public function test_to_array(): void
    {
        $bounds = new Bounds;
        self::assertSame([Lower::Inclusive, Upper::Exclusive], $bounds->toArray());
    }
}
