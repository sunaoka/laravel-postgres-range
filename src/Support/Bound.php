<?php

declare(strict_types=1);

namespace Sunaoka\LaravelPostgres\Support;

use Sunaoka\LaravelPostgres\Types\Bounds\Lower;
use Sunaoka\LaravelPostgres\Types\Bounds\Upper;

/**
 * @template TValue
 */
class Bound
{
    /**
     * @param  TValue  $value
     */
    public function __construct(private mixed $value, private Lower|Upper $bound) {}

    /**
     * @return TValue
     */
    public function getValue(): mixed
    {
        return $this->value;
    }

    public function getBound(): Lower|Upper
    {
        return $this->bound;
    }

    /**
     * @param  callable(TValue): TValue  $callback
     * @return self<TValue>
     */
    public function toInclusive(callable $callback): self
    {
        if ($this->bound === Lower::Inclusive || $this->bound === Upper::Inclusive) {
            return $this;
        }

        if ($this->bound === Lower::Exclusive) {
            $this->bound = Lower::Inclusive;
        } elseif ($this->bound === Upper::Exclusive) {
            $this->bound = Upper::Inclusive;
        }

        $this->value = $callback($this->value);

        return $this;
    }

    /**
     * @param  callable(TValue): TValue  $callback
     * @return self<TValue>
     */
    public function toExclusive(callable $callback): self
    {
        if ($this->bound === Lower::Exclusive || $this->bound === Upper::Exclusive) {
            return $this;
        }

        if ($this->bound === Lower::Inclusive) {
            $this->bound = Lower::Exclusive;
        } elseif ($this->bound === Upper::Inclusive) {
            $this->bound = Upper::Exclusive;
        }

        $this->value = $callback($this->value);

        return $this;
    }

    /**
     * @return array{TValue, Lower|Upper}
     */
    public function toArray(): array
    {
        return [
            $this->getValue(),
            $this->getBound(),
        ];
    }
}
