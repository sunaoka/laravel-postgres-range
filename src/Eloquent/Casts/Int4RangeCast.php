<?php

declare(strict_types=1);

namespace Sunaoka\LaravelPostgres\Eloquent\Casts;

use Sunaoka\LaravelPostgres\Types\Int4Range;
use Sunaoka\LaravelPostgres\Types\Range;

/**
 * @template TSet
 *
 * @extends RangeCast<Int4Range, TSet>
 */
class Int4RangeCast extends RangeCast
{
    /**
     * @return Int4Range
     */
    public function factory(array $matches): Range
    {
        return new Int4Range($matches[0], $matches[1], $matches[2], $matches[3]);
    }
}
