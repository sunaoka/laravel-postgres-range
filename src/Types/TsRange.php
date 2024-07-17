<?php

declare(strict_types=1);

namespace Sunaoka\LaravelPostgres\Types;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Date;
use Sunaoka\LaravelPostgres\Types\Bounds\Lower;
use Sunaoka\LaravelPostgres\Types\Bounds\Upper;

/**
 * @extends Range<Carbon|string, Carbon>
 */
class TsRange extends Range
{
    private string $format;

    /**
     * @param  string  $format  The format of the outputted date string
     */
    public function __construct(
        $lower = null,
        $upper = null,
        Lower $lowerBound = Lower::Inclusive,
        Upper $upperBound = Upper::Exclusive,
        string $format = 'Y-m-d H:i:s'
    ) {
        $this->format = $format;

        parent::__construct($lower, $upper, $lowerBound, $upperBound);
    }

    /**
     * @param  string  $boundary
     */
    protected function transform(mixed $boundary): Carbon
    {
        return Date::parse($boundary);
    }

    public function __toString()
    {
        $lower = '';
        if ($this->lower() !== null) {
            $lower = sprintf('"%s"', $this->lower()->format($this->format));
        }

        $upper = '';
        if ($this->upper() !== null) {
            $upper = sprintf('"%s"', $this->upper()->format($this->format));
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
