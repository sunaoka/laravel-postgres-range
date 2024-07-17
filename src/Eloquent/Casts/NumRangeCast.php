<?php

declare(strict_types=1);

namespace Sunaoka\LaravelPostgres\Eloquent\Casts;

use Sunaoka\LaravelPostgres\Types\Bounds\Lower;
use Sunaoka\LaravelPostgres\Types\Bounds\Upper;
use Sunaoka\LaravelPostgres\Types\NumRange;

/**
 * @template TSet
 *
 * @extends RangeCast<NumRange, TSet>
 */
class NumRangeCast extends RangeCast
{
    /**
     * @param  array{float|null, float|null, Lower, Upper}  $matches
     */
    public function factory(array $matches): NumRange
    {
        return new NumRange($matches[0], $matches[1], $matches[2], $matches[3]);
    }
}
