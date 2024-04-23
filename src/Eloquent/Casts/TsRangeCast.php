<?php

declare(strict_types=1);

namespace Sunaoka\LaravelPostgres\Eloquent\Casts;

use Sunaoka\LaravelPostgres\Types\Range;
use Sunaoka\LaravelPostgres\Types\TsRange;

/**
 * @template TSet
 *
 * @extends RangeCast<TsRange, TSet>
 */
class TsRangeCast extends RangeCast
{
    /**
     * @return TsRange
     */
    public function factory(array $matches): Range
    {
        return new TsRange($matches[2], $matches[3], $matches[1], $matches[4]);
    }
}
