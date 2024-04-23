<?php

declare(strict_types=1);

namespace Sunaoka\LaravelPostgres\Types;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Date;

/**
 * @extends Range<Carbon, string>
 */
class TsRange extends Range
{
    /**
     * @param  string  $boundary
     */
    protected function transform($boundary): Carbon
    {
        return Date::parse($boundary);
    }
}
