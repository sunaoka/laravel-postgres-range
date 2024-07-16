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
        $lower = optional($lower, $this->transform(...));
        $upper = optional($upper, $this->transform(...));

        if ($canonicalize) {
            [$lower, $lowerBound] = $this->toInclusiveLower($lower, $lowerBound);
            [$upper, $upperBound] = $this->toExclusiveUpper($upper, $upperBound);
        }

        parent::__construct($lower, $upper, $lowerBound, $upperBound);
    }

    protected function transform($boundary): int
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
    private function inclement(mixed $value): ?int
    {
        return optional($value, static fn ($value): int => $value + 1);
    }

    /**
     * @param  int|null  $value
     */
    private function decrement(mixed $value): ?int
    {
        return optional($value, static fn ($value): int => $value - 1);
    }
}
