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

        if ($canonicalize) {
            [$lower, $upper, $lowerBound, $upperBound] = $this->canonicalize($lower, $upper, $lowerBound, $upperBound);
        }

        parent::__construct($lower, $upper, $lowerBound, $upperBound);
    }

    protected function canonicalize(?string $lower, ?string $upper, Lower $lowerBound, Upper $upperBound): array
    {
        if ($lower !== null && $lowerBound === Lower::Exclusive) {
            $lower = Date::parse($lower)->addDay()->format($this->format);
            $lowerBound = Lower::Inclusive;
        }

        if ($upper !== null && $upperBound === Upper::Inclusive) {
            $upper = Date::parse($upper)->addDay()->format($this->format);
            $upperBound = Upper::Exclusive;
        }

        return [$lower, $upper, $lowerBound, $upperBound];
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
        $lower = $this->lower();
        if ($lower !== null && $this->bounds()->lower() === Lower::Exclusive) {
            $lower = $lower->addDay();
        }

        $upper = $this->upper();
        if ($upper !== null && $this->bounds()->upper() === Upper::Exclusive) {
            $upper = $upper->subDay();
        }

        return new self($lower?->format($this->format), $upper?->format($this->format), Lower::Inclusive, Upper::Inclusive, $this->format, false);
    }

    public function toExclusive(): self
    {
        $lower = $this->lower();
        if ($lower !== null && $this->bounds()->lower() === Lower::Inclusive) {
            $lower = $lower->subDay();
        }

        $upper = $this->upper();
        if ($upper !== null && $this->bounds()->upper() === Upper::Inclusive) {
            $upper = $upper->addDay();
        }

        return new self($lower?->format($this->format), $upper?->format($this->format), Lower::Exclusive, Upper::Exclusive, $this->format, false);
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
}
