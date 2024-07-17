<?php

declare(strict_types=1);

namespace Sunaoka\LaravelPostgres\Types\Concerns;

use Sunaoka\LaravelPostgres\Support\Bound;
use Sunaoka\LaravelPostgres\Types\Bounds\Lower;
use Sunaoka\LaravelPostgres\Types\Bounds\Upper;
use Sunaoka\LaravelPostgres\Types\Range;

/**
 * @template TValue
 *
 * @phpstan-require-extends Range
 *
 * @phpstan-require-implements \Sunaoka\LaravelPostgres\Types\Contracts\Boundary<TValue>
 */
trait Boundary
{
    /**
     * @param  TValue  $lower
     * @return array{TValue, Lower}
     */
    protected function toInclusiveLower(mixed $lower, Lower $lowerBound): array
    {
        /** @var array{TValue, Lower} */
        return (new Bound($lower, $lowerBound))
            ->toInclusive($this->inclement(...))
            ->toArray();
    }

    /**
     * @param  TValue  $lower
     * @return array{TValue, Lower}
     */
    protected function toExclusiveLower(mixed $lower, Lower $lowerBound): array
    {
        /** @var array{TValue, Lower} */
        return (new Bound($lower, $lowerBound))
            ->toExclusive($this->decrement(...))
            ->toArray();
    }

    /**
     * @param  TValue  $upper
     * @return array{TValue, Upper}
     */
    protected function toInclusiveUpper(mixed $upper, Upper $upperBound): array
    {
        /** @var array{TValue, Upper} */
        return (new Bound($upper, $upperBound))
            ->toInclusive($this->decrement(...))
            ->toArray();
    }

    /**
     * @param  TValue  $upper
     * @return array{TValue, Upper}
     */
    protected function toExclusiveUpper(mixed $upper, Upper $upperBound): array
    {
        /** @var array{TValue, Upper} */
        return (new Bound($upper, $upperBound))
            ->toExclusive($this->inclement(...))
            ->toArray();
    }
}
