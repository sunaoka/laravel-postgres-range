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
        string $format = 'Y-m-d'
    ) {
        $this->format = $format;

        if ($lower !== null && $lowerBound === Lower::Exclusive) {
            $lower = Date::parse($lower)->addDay()->format($format);
            $lowerBound = Lower::Inclusive;
        }

        if ($upper !== null && $upperBound === Upper::Inclusive) {
            $upper = Date::parse($upper)->addDay()->format($format);
            $upperBound = Upper::Exclusive;
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
