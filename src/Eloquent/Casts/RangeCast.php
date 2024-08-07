<?php

declare(strict_types=1);

namespace Sunaoka\LaravelPostgres\Eloquent\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use Sunaoka\LaravelPostgres\Types\Bounds\Lower;
use Sunaoka\LaravelPostgres\Types\Bounds\Upper;
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
     * @param  Model  $model
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
     * @param  Model  $model
     * @param  TSet|null  $value
     * @param  array<string, mixed>  $attributes
     * @return null[]|string[]
     */
    public function set($model, string $key, $value, array $attributes): array
    {
        return [
            $key => ($value !== null) ? (string) $value : null,
        ];
    }

    /**
     * @return array{string|null, string|null, Lower, Upper}|array{}
     */
    protected function parse(string $value): array
    {
        if (preg_match('/([\[(])"?(.*?)"?,"?(.*?)"?([])])/', $value, $matches) !== 1) {
            return [];
        }

        /** @var array{string, '['|'(', string, string, ']'|')'} $matches */
        if (strtolower($matches[2]) === '-infinity' || strtolower($matches[2]) === 'infinity') {
            $matches[2] = null;
        }

        if (strtolower($matches[3]) === '-infinity' || strtolower($matches[3]) === 'infinity') {
            $matches[3] = null;
        }

        return [
            $matches[2] !== '' ? $matches[2] : null,
            $matches[3] !== '' ? $matches[3] : null,
            Lower::from($matches[1]),
            Upper::from($matches[4]),
        ];
    }

    /**
     * @param  array{string|null, string|null, Lower, Upper}  $matches
     * @return TGet
     */
    abstract public function factory(array $matches): Range;
}
