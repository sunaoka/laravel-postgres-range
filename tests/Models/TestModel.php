<?php

declare(strict_types=1);

namespace Sunaoka\LaravelPostgres\Tests\Models;

use Illuminate\Database\Eloquent\Model;
use Sunaoka\LaravelPostgres\Eloquent\Casts\DateRangeCast;
use Sunaoka\LaravelPostgres\Eloquent\Casts\Int4RangeCast;
use Sunaoka\LaravelPostgres\Eloquent\Casts\Int8RangeCast;
use Sunaoka\LaravelPostgres\Eloquent\Casts\NumRangeCast;
use Sunaoka\LaravelPostgres\Eloquent\Casts\TsRangeCast;
use Sunaoka\LaravelPostgres\Eloquent\Casts\TsTzRangeCast;
use Sunaoka\LaravelPostgres\Types\DateRange;
use Sunaoka\LaravelPostgres\Types\Int4Range;
use Sunaoka\LaravelPostgres\Types\Int8Range;
use Sunaoka\LaravelPostgres\Types\NumRange;
use Sunaoka\LaravelPostgres\Types\TsRange;
use Sunaoka\LaravelPostgres\Types\TsTzRange;

/**
 * @property array $json
 * @property TsRange $ts_range
 * @property TsTzRange $ts_tz_range
 * @property DateRange $date_range
 * @property Int4Range $int4_range
 * @property Int8Range $int8_range
 * @property NumRange $num_range
 */
class TestModel extends Model
{
    protected $table = 'tests';

    protected $fillable = [
        'ts_range',
        'ts_tz_range',
        'date_range',
        'int4_range',
        'int8_range',
        'num_range',
    ];

    protected $casts = [
        'ts_range' => TsRangeCast::class,
        'ts_tz_range' => TsTzRangeCast::class,
        'date_range' => DateRangeCast::class,
        'int4_range' => Int4RangeCast::class,
        'int8_range' => Int8RangeCast::class,
        'num_range' => NumRangeCast::class,
    ];
}
