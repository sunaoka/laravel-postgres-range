<?php

declare(strict_types=1);

namespace Sunaoka\LaravelPostgres\Types\Bounds;

enum Upper: string
{
    case Inclusive = ']';

    case Exclusive = ')';
}
