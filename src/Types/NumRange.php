<?php

declare(strict_types=1);

namespace Sunaoka\LaravelPostgres\Types;

/**
 * @extends Range<float, float>
 */
class NumRange extends Range
{
    protected function transform($boundary): float
    {
        return (float) $boundary;
    }
}
