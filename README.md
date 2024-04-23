# PostgreSQL Range Types for Laravel 8.x to 11

[![Latest Stable Version](https://poser.pugx.org/sunaoka/laravel-postgres-range/v/stable)](https://packagist.org/packages/sunaoka/laravel-postgres-range)
[![License](https://poser.pugx.org/sunaoka/laravel-postgres-range/license)](https://packagist.org/packages/sunaoka/laravel-postgres-range)
[![PHP from Packagist](https://img.shields.io/packagist/php-v/sunaoka/laravel-postgres-range)](composer.json)
[![Laravel](https://img.shields.io/badge/laravel-%3E=%208.x-red)](https://laravel.com/)
[![Test](https://github.com/sunaoka/laravel-postgres-range/actions/workflows/test.yml/badge.svg)](https://github.com/sunaoka/laravel-postgres-range/actions/workflows/test.yml)
[![codecov](https://codecov.io/gh/sunaoka/laravel-postgres-range/branch/develop/graph/badge.svg)](https://codecov.io/gh/sunaoka/laravel-postgres-range)

----

## Installation

```bash
composer require sunaoka/laravel-postgres-range
```

## Features

- Range Types
    - [x] int4range — Range of integer
    - [ ] int8range — Range of bigint
    - [ ] numrange — Range of numeric
    - [x] tsrange — Range of timestamp without time zone
    - [ ] tstzrange — Range of timestamp with time zone
    - [ ] daterange — Range of date

## Usage

### Table

```sql
CREATE TABLE some_models
(
    id bigserial PRIMARY KEY NOT NULL,
    code text NOT NULL,
    term tsrange NOT NULL,
    CONSTRAINT code_uq UNIQUE (code)
);
```

### Model

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Sunaoka\LaravelPostgres\Eloquent\Casts\Int4RangeCast;
use Sunaoka\LaravelPostgres\Eloquent\Casts\TsRangeCast;

class SomeModel extends Model
{
    protected $casts = [
        'int4' => Int4RangeCast::class, // int4range
        'ts' => TsRangeCast::class, // tsrange
    ];
}
```

### Range Types

```php
use Sunaoka\LaravelPostgres\Types\Bounds\Lower;
use Sunaoka\LaravelPostgres\Types\Bounds\Upper;
use Sunaoka\LaravelPostgres\Types\Int4Range;
use Sunaoka\LaravelPostgres\Types\TsRange;

$some = new SomeModel();
$some->int4 = new Int4Range(1, 10, Lower::Inclusive, Upper::Exclusive);
$some->ts = new TsRange('2020-10-01 00:00:00', '2020-10-01 23:59:59', Lower::Inclusive, Upper::Exclusive);
$some->save();
```

```sql
insert into "some_models" ( "int4", "ts") values
  ('[1,10)', '[2020-10-01 00:00:00,2020-10-01 23:59:59)')
  returning "id";
```

```php
$some = SomeModel::find(1);

echo $some->int4->lower(); // lower() or from()
// => 1
echo $some->int4->upper(); // upper() or to()
// => 10

echo $some->ts->lower()->format('Y-m-d H:i:s'); // lower() or from()
// => 2020-10-01 00:00:00
echo $some->ts->upper()->format('Y-m-d H:i:s'); // upper() or to()
// => 2020-10-01 23:59:59
```
