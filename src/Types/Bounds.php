<?php

declare(strict_types=1);

namespace Sunaoka\LaravelPostgres\Types;

use Illuminate\Contracts\Support\Arrayable;
use Sunaoka\LaravelPostgres\Types\Bounds\Lower;
use Sunaoka\LaravelPostgres\Types\Bounds\Upper;

/**
 * @implements  Arrayable<int, Lower|Upper>
 */
final class Bounds implements Arrayable
{
    private Lower $lower;

    private Upper $upper;

    public function __construct(Lower $lower = Lower::Inclusive, Upper $upper = Upper::Exclusive)
    {
        $this->lower = $lower;
        $this->upper = $upper;
    }

    public function lower(): Lower
    {
        return $this->lower;
    }

    public function upper(): Upper
    {
        return $this->upper;
    }

    /**
     * @return array<int, Lower|Upper>
     */
    public function toArray(): array
    {
        return [$this->lower(), $this->upper()];
    }
}
