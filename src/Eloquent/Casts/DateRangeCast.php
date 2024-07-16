<?php

declare(strict_types=1);

namespace Sunaoka\LaravelPostgres\Eloquent\Casts;

use Sunaoka\LaravelPostgres\Types\DateRange;
use Sunaoka\LaravelPostgres\Types\Range;

/**
 * @template TSet
 *
 * @extends RangeCast<DateRange, TSet>
 */
class DateRangeCast extends RangeCast
{
    /**
     * @return DateRange
     */
    public function factory(array $matches): Range
    {
        return new DateRange($matches[0], $matches[1], $matches[2], $matches[3]);
    }
}
