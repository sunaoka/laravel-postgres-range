<?php

declare(strict_types=1);

namespace Sunaoka\LaravelPostgres\Eloquent\Casts;

use Sunaoka\LaravelPostgres\Types\Bounds\Lower;
use Sunaoka\LaravelPostgres\Types\Bounds\Upper;
use Sunaoka\LaravelPostgres\Types\Int4Range;

/**
 * @template TSet
 *
 * @extends RangeCast<Int4Range, TSet>
 */
class Int4RangeCast extends RangeCast
{
    /**
     * @param  array{int|null, int|null, Lower, Upper}  $matches
     */
    public function factory(array $matches): Int4Range
    {
        return new Int4Range($matches[0], $matches[1], $matches[2], $matches[3]);
    }
}
