<?php

declare(strict_types=1);

namespace Sunaoka\LaravelPostgres\Types\Bounds;

enum Lower: string
{
    case Inclusive = '[';

    case Exclusive = '(';
}
