<?php

declare(strict_types=1);

namespace Sunaoka\LaravelPostgres\Types;

use Carbon\CarbonImmutable;

/**
 * @extends Range<CarbonImmutable, string>
 */
class TsRange extends Range
{
    /**
     * @param  string  $boundary
     */
    protected function transform($boundary): CarbonImmutable
    {
        return CarbonImmutable::parse($boundary);
    }
}
