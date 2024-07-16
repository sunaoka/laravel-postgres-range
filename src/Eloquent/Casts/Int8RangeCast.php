<?php

declare(strict_types=1);

namespace Sunaoka\LaravelPostgres\Eloquent\Casts;

use Sunaoka\LaravelPostgres\Types\Int8Range;
use Sunaoka\LaravelPostgres\Types\Range;

/**
 * @template TSet
 *
 * @extends RangeCast<Int8Range, TSet>
 */
class Int8RangeCast extends RangeCast
{
    /**
     * @return Int8Range
     */
    public function factory(array $matches): Range
    {
        return new Int8Range($matches[0], $matches[1], $matches[2], $matches[3]);
    }
}
