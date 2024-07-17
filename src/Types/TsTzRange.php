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
class TsTzRange extends Range
{
    private string $format;

    private string $timezone;

    /**
     * @param  string  $format  The format of the outputted date string
     */
    public function __construct(
        $lower = null,
        $upper = null,
        Lower $lowerBound = Lower::Inclusive,
        Upper $upperBound = Upper::Exclusive,
        string $format = 'Y-m-d H:i:sP',
        ?string $timezone = null
    ) {
        $this->format = $format;
        $this->timezone = $timezone ?? date_default_timezone_get();

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
            $lower = sprintf('"%s"', $this->lower()->setTimezone($this->timezone)->format($this->format));
        }

        $upper = '';
        if ($this->upper() !== null) {
            $upper = sprintf('"%s"', $this->upper()->setTimezone($this->timezone)->format($this->format));
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
     * @return array<int, Carbon|null>
     */
    public function toArray(): array
    {
        return [
            $this->lower()?->setTimezone($this->timezone),
            $this->upper()?->setTimezone($this->timezone),
        ];
    }
}
