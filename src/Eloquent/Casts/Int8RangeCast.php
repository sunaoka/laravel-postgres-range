<?php

declare(strict_types=1);

namespace Sunaoka\LaravelPostgres\Eloquent\Casts;

use Sunaoka\LaravelPostgres\Types\Bounds\Lower;
use Sunaoka\LaravelPostgres\Types\Bounds\Upper;
use Sunaoka\LaravelPostgres\Types\Int8Range;

/**
 * @template TSet
 *
 * @extends RangeCast<Int8Range, TSet>
 */
class Int8RangeCast extends RangeCast
{
    /**
     * @param  array{int|null, int|null, Lower, Upper}  $matches
     */
    public function factory(array $matches): Int8Range
    {
        return new Int8Range($matches[0], $matches[1], $matches[2], $matches[3]);
    }
}
