<?php

declare(strict_types=1);

namespace Sunaoka\LaravelPostgres\Types\Contracts;

use Sunaoka\LaravelPostgres\Types\Range;

/**
 * @template TValue
 *
 * @phpstan-require-extends Range
 */
interface Boundary
{
    /**
     * @param  TValue  $value
     * @return TValue
     */
    public function inclement(mixed $value): mixed;

    /**
     * @param  TValue  $value
     * @return TValue
     */
    public function decrement(mixed $value): mixed;
}
