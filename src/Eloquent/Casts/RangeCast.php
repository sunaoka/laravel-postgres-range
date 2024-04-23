<?php

declare(strict_types=1);

namespace Sunaoka\LaravelPostgres\Eloquent\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Sunaoka\LaravelPostgres\Types\Range;

/**
 * @template TGet of Range
 * @template TSet
 *
 * @implements CastsAttributes<TGet, TSet>
 */
abstract class RangeCast implements CastsAttributes
{
    /**
     * Transform the attribute from the underlying model values.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string|null  $value
     * @param  array<string, mixed>  $attributes
     * @return TGet|null
     */
    public function get($model, string $key, $value, array $attributes)
    {
        if ($value === null) {
            return null;
        }

        $matches = $this->parse($value);
        if (empty($matches)) {
            return null;
        }

        return $this->factory($matches);
    }

    /**
     * Transform the attribute to its underlying model values.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  TSet|null  $value
     * @param  array<string, mixed>  $attributes
     * @return null[]|string[]
     */
    public function set($model, string $key, $value, array $attributes)
    {
        return [
            $key => ($value !== null) ? (string) $value : null,
        ];
    }

    protected function parse(string $value): array
    {
        if (preg_match('/([\[(])"?(.*?)"?,"?(.*?)"?([])])/', $value, $matches) !== 1) {
            return [];
        }

        if (strtolower($matches[3]) === 'infinity') {
            $matches[3] = null;
        }

        return $matches;
    }

    /**
     * @return TGet
     */
    abstract public function factory(array $matches): Range;
}
