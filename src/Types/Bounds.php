<?php

declare(strict_types=1);

namespace Sunaoka\LaravelPostgres\Types;

use Sunaoka\LaravelPostgres\Types\Bounds\Lower;
use Sunaoka\LaravelPostgres\Types\Bounds\Upper;

final class Bounds
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
}
