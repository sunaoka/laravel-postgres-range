<?php

declare(strict_types=1);

namespace Sunaoka\LaravelPostgres\Types;

/**
 * @template TType
 * @template TBound
 */
abstract class Range
{
    /**
     * @var TBound|null
     */
    protected $lower;

    /**
     * @var TBound|null
     */
    protected $upper;

    /**
     * @var Bounds
     */
    protected $bounds;

    /**
     * Get lower bound
     *
     * @return TType|null
     */
    public function lower()
    {
        return $this->lower ? $this->transform($this->lower) : null;
    }

    /**
     * Get upper bound
     *
     * @return TType|null
     */
    public function upper()
    {
        return $this->upper ? $this->transform($this->upper) : null;
    }

    /**
     * Alias of lower()
     *
     * @return TType|null
     */
    public function from()
    {
        return $this->lower();
    }

    /**
     * Alias of upper()
     *
     * @return TType|null
     */
    public function to()
    {
        return $this->upper();
    }

    /**
     * @param  TBound  $boundary
     * @return TType
     */
    abstract protected function transform($boundary);

    /**
     * @param  TBound|null  $lower
     * @param  TBound|null  $upper
     * @param  Bounds::INCLUSIVE_LOWER|Bounds::EXCLUSIVE_LOWER  $lowerBound
     * @param  Bounds::INCLUSIVE_UPPER|Bounds::EXCLUSIVE_UPPER  $upperBound
     */
    public function __construct($lower = null, $upper = null, string $lowerBound = Bounds::INCLUSIVE_LOWER, string $upperBound = Bounds::EXCLUSIVE_UPPER)
    {
        $this->lower = $lower ?: null;
        $this->upper = $upper ?: null;
        $this->bounds = new Bounds($lowerBound, $upperBound);
    }

    public function bounds(): Bounds
    {
        return $this->bounds;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return "{$this->bounds()->lower()}{$this->lower()},{$this->upper()}{$this->bounds()->upper()}";
    }
}
