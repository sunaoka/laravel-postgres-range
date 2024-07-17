<?php

declare(strict_types=1);

namespace Sunaoka\LaravelPostgres\Eloquent\Casts;

use Sunaoka\LaravelPostgres\Types\Bounds\Lower;
use Sunaoka\LaravelPostgres\Types\Bounds\Upper;
use Sunaoka\LaravelPostgres\Types\TsTzRange;

/**
 * @template TSet
 *
 * @extends RangeCast<TsTzRange, TSet>
 */
class TsTzRangeCast extends RangeCast
{
    /**
     * @param  array{string|null, string|null, Lower, Upper}  $matches
     */
    public function factory(array $matches): TsTzRange
    {
        return new TsTzRange($matches[0], $matches[1], $matches[2], $matches[3]);
    }
}
