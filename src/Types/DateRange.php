<?php

declare(strict_types=1);

namespace Sunaoka\LaravelPostgres\Types;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Date;
use Sunaoka\LaravelPostgres\Types\Bounds\Lower;
use Sunaoka\LaravelPostgres\Types\Bounds\Upper;

/**
 * @extends Range<Carbon, string>
 */
class DateRange extends Range
{
    /**
     * @use Concerns\Boundary<Carbon|null>
     */
    use Concerns\Boundary;

    private string $format;

    public function __construct(
        $lower = null,
        $upper = null,
        Lower $lowerBound = Lower::Inclusive,
        Upper $upperBound = Upper::Exclusive,
        string $format = 'Y-m-d',
        bool $canonicalize = true
    ) {
        $this->format = $format;

        $lower = optional($lower, $this->transform(...));
        $upper = optional($upper, $this->transform(...));

        if ($canonicalize) {
            [$lower, $lowerBound] = $this->toInclusiveLower($lower, $lowerBound);
            [$upper, $upperBound] = $this->toExclusiveUpper($upper, $upperBound);
        }

        parent::__construct($lower, $upper, $lowerBound, $upperBound);
    }

    /**
     * @param  string  $boundary
     */
    protected function transform($boundary): Carbon
    {
        return Date::parse($boundary);
    }

    public function toInclusive(): self
    {
        [$lower, $lowerBound] = $this->toInclusiveLower($this->lower(), $this->bounds()->lower());
        [$upper, $upperBound] = $this->toInclusiveUpper($this->upper(), $this->bounds()->upper());

        return new self($lower, $upper, $lowerBound, $upperBound, $this->format, false);
    }

    public function toExclusive(): self
    {
        [$lower, $lowerBound] = $this->toExclusiveLower($this->lower(), $this->bounds()->lower());
        [$upper, $upperBound] = $this->toExclusiveUpper($this->upper(), $this->bounds()->upper());

        return new self($lower, $upper, $lowerBound, $upperBound, $this->format, false);
    }

    public function __toString()
    {
        $lower = '';
        if ($this->lower() !== null) {
            $lower = sprintf('%s', $this->lower()->format($this->format));
        }

        $upper = '';
        if ($this->upper() !== null) {
            $upper = sprintf('%s', $this->upper()->format($this->format));
        }

        return sprintf(
            '%s%s,%s%s',
            $this->bounds()->lower()->value,
            $lower,
            $upper,
            $this->bounds()->upper()->value
        );
    }

    /**
     * @param  Carbon|null  $value
     */
    private function inclement(mixed $value): ?Carbon
    {
        return $value?->addDay();
    }

    /**
     * @param  Carbon|null  $value
     */
    private function decrement(mixed $value): ?Carbon
    {
        return $value?->subDay();
    }
}
