<?php

declare(strict_types=1);

namespace Sunaoka\LaravelPostgres\Types;

use Illuminate\Contracts\Support\Arrayable;
use Sunaoka\LaravelPostgres\Types\Bounds\Lower;
use Sunaoka\LaravelPostgres\Types\Bounds\Upper;

/**
 * @template TType
 * @template TValue
 *
 * @implements  Arrayable<int, TType|null>
 */
abstract class Range implements \Stringable, Arrayable
{
    /**
     * @var TType|null
     */
    protected $lower;

    /**
     * @var TType|null
     */
    protected $upper;

    protected Bounds $bounds;

    /**
     * Get lower bound
     *
     * @return TValue|null
     */
    public function lower(): mixed
    {
        if ($this->lower === null) {
            return null;
        }

        return $this->transform($this->lower);
    }

    /**
     * Get upper bound
     *
     * @return TValue|null
     */
    public function upper(): mixed
    {
        if ($this->upper === null) {
            return null;
        }

        return $this->transform($this->upper);
    }

    /**
     * Alias of lower()
     *
     * @return TValue|null
     *
     * @codeCoverageIgnore
     */
    public function from(): mixed
    {
        return $this->lower();
    }

    /**
     * Alias of upper()
     *
     * @return TValue|null
     *
     * @codeCoverageIgnore
     */
    public function to(): mixed
    {
        return $this->upper();
    }

    /**
     * @param  TType  $boundary
     * @return TValue
     */
    abstract protected function transform(mixed $boundary): mixed;

    /**
     * @param  TType|null  $lower
     * @param  TType|null  $upper
     */
    public function __construct(
        $lower = null,
        $upper = null,
        Lower $lowerBound = Lower::Inclusive,
        Upper $upperBound = Upper::Exclusive
    ) {
        $this->lower = $lower;
        $this->upper = $upper;

        if ($lower === null && $lowerBound === Lower::Inclusive) {
            $lowerBound = Lower::Exclusive;
        }

        if ($upper === null && $upperBound === Upper::Inclusive) {
            $upperBound = Upper::Exclusive;
        }

        $this->bounds = new Bounds($lowerBound, $upperBound);
    }

    public function bounds(): Bounds
    {
        return $this->bounds;
    }

    /**
     * @return self<TType, TValue>
     *
     * @codeCoverageIgnore
     */
    public function toInclusive(): self
    {
        throw new \LogicException('Not implemented');
    }

    /**
     * @return self<TType, TValue>
     *
     * @codeCoverageIgnore
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
        return sprintf(
            '%s%s,%s%s',
            $this->bounds()->lower()->value,
            (string) $this->lower(),
            (string) $this->upper(),
            $this->bounds()->upper()->value
        );
    }

    /**
     * @return array<int, TValue|null>
     */
    public function toArray(): array
    {
        return [
            $this->lower(),
            $this->upper(),
        ];
    }
}
