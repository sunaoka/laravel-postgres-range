<?php

declare(strict_types=1);

namespace Sunaoka\LaravelPostgres\Types;

use Illuminate\Contracts\Support\Arrayable;
use Sunaoka\LaravelPostgres\Types\Bounds\Lower;
use Sunaoka\LaravelPostgres\Types\Bounds\Upper;

/**
 * @template TType
 * @template TBound
 *
 * @implements  Arrayable<int, TType|null>
 */
abstract class Range implements \Stringable, Arrayable
{
    /**
     * @var TBound|null
     */
    protected $lower;

    /**
     * @var TBound|null
     */
    protected $upper;

    protected Bounds $bounds;

    /**
     * Get lower bound
     *
     * @return TType|null
     */
    public function lower()
    {
        return $this->lower !== null ? $this->transform($this->lower) : null;
    }

    /**
     * Get upper bound
     *
     * @return TType|null
     */
    public function upper()
    {
        return $this->upper !== null ? $this->transform($this->upper) : null;
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
     */
    public function __construct($lower = null, $upper = null, Lower $lowerBound = Lower::Inclusive, Upper $upperBound = Upper::Exclusive)
    {
        $this->lower = $lower;
        $this->upper = $upper;
        $this->bounds = new Bounds($lowerBound, $upperBound);
    }

    public function bounds(): Bounds
    {
        return $this->bounds;
    }

    /**
     * @return self<TType, TBound>
     */
    public function toInclusive(): self
    {
        throw new \LogicException('Not implemented');
    }

    /**
     * @return self<TType, TBound>
     */
    public function toExclusive(): self
    {
        throw new \LogicException('Not implemented');
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return "{$this->bounds()->lower()->value}{$this->lower()},{$this->upper()}{$this->bounds()->upper()->value}";
    }

    /**
     * @return array<int, TType|null>
     */
    public function toArray(): array
    {
        return [$this->lower(), $this->upper()];
    }
}
