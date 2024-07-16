<?php

declare(strict_types=1);

namespace Sunaoka\LaravelPostgres\Eloquent\Casts;

use Sunaoka\LaravelPostgres\Types\Range;
use Sunaoka\LaravelPostgres\Types\TsTzRange;

/**
 * @template TSet
 *
 * @extends RangeCast<TsTzRange, TSet>
 */
class TsTzRangeCast extends RangeCast
{
    /**
     * @return TsTzRange
     */
    public function factory(array $matches): Range
    {
        return new TsTzRange($matches[0], $matches[1], $matches[2], $matches[3]);
    }
}
