<?php

declare(strict_types=1);

namespace Sunaoka\LaravelPostgres\Eloquent\Casts;

use Sunaoka\LaravelPostgres\Types\Bounds\Lower;
use Sunaoka\LaravelPostgres\Types\Bounds\Upper;
use Sunaoka\LaravelPostgres\Types\DateRange;

/**
 * @template TSet
 *
 * @extends RangeCast<DateRange, TSet>
 */
class DateRangeCast extends RangeCast
{
    /**
     * @param  array{string|null, string|null, Lower, Upper}  $matches
     */
    public function factory(array $matches): DateRange
    {
        return new DateRange($matches[0], $matches[1], $matches[2], $matches[3]);
    }
}
