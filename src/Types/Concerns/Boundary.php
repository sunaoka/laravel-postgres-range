<?php

declare(strict_types=1);

namespace Sunaoka\LaravelPostgres\Types\Concerns;

use Sunaoka\LaravelPostgres\Support\Bound;
use Sunaoka\LaravelPostgres\Types\Bounds\Lower;
use Sunaoka\LaravelPostgres\Types\Bounds\Upper;

/**
 * @template TValue
 */
trait Boundary
{
    /**
     * @param  TValue  $lower
     */
    protected function toInclusiveLower(mixed $lower, Lower $lowerBound): array
    {
        return (new Bound($lower, $lowerBound))->toInclusive($this->inclement(...))->toArray();
    }

    /**
     * @param  TValue  $lower
     */
    protected function toExclusiveLower(mixed $lower, Lower $lowerBound): array
    {
        return (new Bound($lower, $lowerBound))->toExclusive($this->decrement(...))->toArray();
    }

    /**
     * @param  TValue  $upper
     */
    protected function toInclusiveUpper(mixed $upper, Upper $upperBound): array
    {
        return (new Bound($upper, $upperBound))->toInclusive($this->decrement(...))->toArray();
    }

    /**
     * @param  TValue  $upper
     */
    protected function toExclusiveUpper(mixed $upper, Upper $upperBound): array
    {
        return (new Bound($upper, $upperBound))->toExclusive($this->inclement(...))->toArray();
    }

    /**
     * @param  TValue  $value
     * @return TValue
     */
    abstract private function inclement(mixed $value): mixed;

    /**
     * @param  TValue  $value
     * @return TValue
     */
    abstract private function decrement(mixed $value): mixed;
}
