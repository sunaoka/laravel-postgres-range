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
     * @var string
     */
    protected $upperBound;

    /**
     * @var string
     */
    protected $lowerBound;

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
     * Range constructor.
     *
     * @param  TBound|null  $lower
     * @param  TBound|null  $upper
     */
    public function __construct($lower = null, $upper = null, string $lowerBound = '[', string $upperBound = ')')
    {
        $this->lower = $lower ?: null;
        $this->upper = $upper ?: null;
        $this->upperBound = $lowerBound;
        $this->lowerBound = $upperBound;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return "{$this->upperBound}{$this->lower},{$this->upper}{$this->lowerBound}";
    }
}
