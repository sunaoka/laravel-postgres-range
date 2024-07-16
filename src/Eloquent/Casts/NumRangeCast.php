<?php

declare(strict_types=1);

namespace Sunaoka\LaravelPostgres\Eloquent\Casts;

use Sunaoka\LaravelPostgres\Types\NumRange;
use Sunaoka\LaravelPostgres\Types\Range;

/**
 * @template TSet
 *
 * @extends RangeCast<NumRange, TSet>
 */
class NumRangeCast extends RangeCast
{
    /**
     * @return NumRange
     */
    public function factory(array $matches): Range
    {
        return new NumRange($matches[0], $matches[1], $matches[2], $matches[3]);
    }
}
