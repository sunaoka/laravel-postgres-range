<?php

declare(strict_types=1);

namespace Sunaoka\LaravelPostgres\Eloquent\Casts;

use Sunaoka\LaravelPostgres\Types\Bounds\Lower;
use Sunaoka\LaravelPostgres\Types\Bounds\Upper;
use Sunaoka\LaravelPostgres\Types\TsRange;

/**
 * @template TSet
 *
 * @extends RangeCast<TsRange, TSet>
 */
class TsRangeCast extends RangeCast
{
    /**
     * @param  array{string|null, string|null, Lower, Upper}  $matches
     */
    public function factory(array $matches): TsRange
    {
        return new TsRange($matches[0], $matches[1], $matches[2], $matches[3]);
    }
}
