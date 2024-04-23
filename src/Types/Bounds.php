<?php

declare(strict_types=1);

namespace Sunaoka\LaravelPostgres\Types;

final class Bounds
{
    public const INCLUSIVE_LOWER = '[';

    public const EXCLUSIVE_LOWER = '(';

    public const INCLUSIVE_UPPER = ']';

    public const EXCLUSIVE_UPPER = ')';

    /**
     * @var string
     */
    private $lower;

    /**
     * @var string
     */
    private $upper;

    /**
     * @param  self::INCLUSIVE_LOWER|self::EXCLUSIVE_LOWER  $lower
     * @param  self::INCLUSIVE_UPPER|self::EXCLUSIVE_UPPER  $upper
     */
    public function __construct(string $lower = self::INCLUSIVE_LOWER, string $upper = self::EXCLUSIVE_UPPER)
    {
        $this->lower = $lower;
        $this->upper = $upper;
    }

    public function lower(): string
    {
        return $this->lower;
    }

    public function upper(): string
    {
        return $this->upper;
    }
}
