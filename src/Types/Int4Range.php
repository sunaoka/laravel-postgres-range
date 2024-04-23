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

        return new self($lower, $upper, Lower::Inclusive, Upper::Inclusive);
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

        return new self($lower, $upper, Lower::Exclusive, Upper::Exclusive);
    }
}
