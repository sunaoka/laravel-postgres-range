<?php

declare(strict_types=1);

namespace Sunaoka\LaravelPostgres\Types;

/**
 * @extends Range<float, float>
 */
class NumRange extends Range
{
    protected function transform(mixed $boundary): float
    {
        return (float) $boundary;
    }
}
