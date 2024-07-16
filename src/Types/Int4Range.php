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
        Upper $upperBound = Upper::Exclusive,
        bool $canonicalize = true
    ) {
        if ($canonicalize) {
            [$lower, $upper, $lowerBound, $upperBound] = $this->canonicalize($lower, $upper, $lowerBound, $upperBound);
        }

        parent::__construct($lower, $upper, $lowerBound, $upperBound);
    }

    protected function canonicalize(int|string|null $lower, int|string|null $upper, Lower $lowerBound, Upper $upperBound): array
    {
        if ($lower !== null && $lowerBound === Lower::Exclusive) {
            $lower++;
            $lowerBound = Lower::Inclusive;
        }

        if ($upper !== null && $upperBound === Upper::Inclusive) {
            $upper++;
            $upperBound = Upper::Exclusive;
        }

        return [$lower, $upper, $lowerBound, $upperBound];
    }

    protected function transform($boundary): int
    {
        return (int) $boundary;
    }

    public function toInclusive(): self
    {
        $lower = $this->lower();
        if ($lower !== null && $this->bounds()->lower() === Lower::Exclusive) {
            $lower++;
        }

        $upper = $this->upper();
        if ($upper !== null && $this->bounds()->upper() === Upper::Exclusive) {
            $upper--;
        }

        return new self($lower, $upper, Lower::Inclusive, Upper::Inclusive, false);
    }

    public function toExclusive(): self
    {
        $lower = $this->lower();
        if ($lower !== null && $this->bounds()->lower() === Lower::Inclusive) {
            $lower--;
        }

        $upper = $this->upper();
        if ($upper !== null && $this->bounds()->upper() === Upper::Inclusive) {
            $upper++;
        }

        return new self($lower, $upper, Lower::Exclusive, Upper::Exclusive, false);
    }
}
