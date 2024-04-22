<?php

declare(strict_types=1);

namespace Sunaoka\LaravelPostgres\Tests\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Sunaoka\LaravelPostgres\Eloquent\Casts\Int4RangeCast;
use Sunaoka\LaravelPostgres\Eloquent\Casts\TsRangeCast;
use Sunaoka\LaravelPostgres\Types\Range;

/**
 * @property array $json
 * @property Range $term
 *
 * @method static Builder|self make($attributes = [])
 */
class TestModel extends Model
{
    protected $table = 'tests';

    protected $fillable = [
        'json',
        'ts_range',
        'int4_range',
    ];

    protected $casts = [
        'json' => 'json',
        'ts_range' => TsRangeCast::class,
        'int4_range' => Int4RangeCast::class,
    ];
}
