<?php

declare(strict_types=1);

namespace Sunaoka\LaravelPostgres\Types;

use Sunaoka\LaravelPostgres\Types\Bounds\Lower;
use Sunaoka\LaravelPostgres\Types\Bounds\Upper;

/**
 * @extends Range<int, int>
 */
class Int4Range extends Range
{
    public function __construct(
        $lower = null,
        $upper = null,
        Lower $lowerBound = Lower::Inclusive,
        Upper $upperBound = Upper::Exclusive
    ) {
        if ($lower !== null && $lowerBound === Lower::Exclusive) {
            $lower++;
            $lowerBound = Lower::Inclusive;
        }

        if ($upper !== null && $upperBound === Upper::Inclusive) {
            $upper++;
            $upperBound = Upper::Exclusive;
        }

        parent::__construct($lower, $upper, $lowerBound, $upperBound);
    }

    protected function transform($boundary): int
    {
        return (int) $boundary;
    }
}
