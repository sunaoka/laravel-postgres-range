<?php

declare(strict_types=1);

namespace Sunaoka\LaravelPostgres\Types;

use Sunaoka\LaravelPostgres\Types\Bounds\Lower;
use Sunaoka\LaravelPostgres\Types\Bounds\Upper;

/**
 * @extends Range<int, int>
 *
 * @implements Contracts\Boundary<int>
 */
class Int4Range extends Range implements Contracts\Boundary
{
    /**
     * @use Concerns\Boundary<int|null>
     */
    use Concerns\Boundary;

    public function __construct(
        $lower = null,
        $upper = null,
        Lower $lowerBound = Lower::Inclusive,
        Upper $upperBound = Upper::Exclusive,
        bool $canonicalize = true
    ) {
        if ($canonicalize) {
            if ($lower !== null) {
                $lower = $this->transform($lower);
            }

            if ($upper !== null) {
                $upper = $this->transform($upper);
            }

            [$lower, $lowerBound] = $this->toInclusiveLower($lower, $lowerBound);
            [$upper, $upperBound] = $this->toExclusiveUpper($upper, $upperBound);
        }

        parent::__construct($lower, $upper, $lowerBound, $upperBound);
    }

    protected function transform(mixed $boundary): int
    {
        return (int) $boundary;
    }

    public function toInclusive(): self
    {
        [$lower, $lowerBound] = $this->toInclusiveLower($this->lower(), $this->bounds()->lower());
        [$upper, $upperBound] = $this->toInclusiveUpper($this->upper(), $this->bounds()->upper());

        return new self($lower, $upper, $lowerBound, $upperBound, false);
    }

    public function toExclusive(): self
    {
        [$lower, $lowerBound] = $this->toExclusiveLower($this->lower(), $this->bounds()->lower());
        [$upper, $upperBound] = $this->toExclusiveUpper($this->upper(), $this->bounds()->upper());

        return new self($lower, $upper, $lowerBound, $upperBound, false);
    }

    /**
     * @param  int|null  $value
     */
    public function inclement(mixed $value): ?int
    {
        return optional($value, static fn ($value): int => $value + 1);
    }

    /**
     * @param  int|null  $value
     */
    public function decrement(mixed $value): ?int
    {
        return optional($value, static fn ($value): int => $value - 1);
    }
}
