<?php

declare(strict_types=1);

namespace Sunaoka\LaravelPostgres\Types;

/**
 * @extends Range<int, int>
 */
class Int4Range extends Range
{
    protected function transform($boundary): int
    {
        return $boundary;
    }
}
