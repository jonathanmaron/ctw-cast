# Package "ctw/ctw-cast"

[![Latest Stable Version](https://poser.pugx.org/ctw/ctw-cast/v/stable)](https://packagist.org/packages/ctw/ctw-cast)
[![GitHub Actions](https://github.com/jonathanmaron/ctw-cast/actions/workflows/tests.yml/badge.svg)](https://github.com/jonathanmaron/ctw-cast/actions/workflows/tests.yml)
[![Scrutinizer Build](https://scrutinizer-ci.com/g/jonathanmaron/ctw-cast/badges/build.png?b=master)](https://scrutinizer-ci.com/g/jonathanmaron/ctw-cast/build-status/master)
[![Scrutinizer Quality](https://scrutinizer-ci.com/g/jonathanmaron/ctw-cast/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/jonathanmaron/ctw-cast/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/jonathanmaron/ctw-cast/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/jonathanmaron/ctw-cast/?branch=master)

Type-safe casting utility for PHP 8.3+ applications with comprehensive error handling.

## Introduction

### Why This Library Exists

Modern PHP development with strict types (`declare(strict_types=1)`) demands precise type handling. However, many data sources in PHP return `mixed` or loosely-typed values:

- **Superglobals** (`$_GET`, `$_POST`, `$_SERVER`, `$_ENV`, `$_COOKIE`, `$_SESSION`) return `mixed`
- **Environment variables** via `getenv()` return `string|false`
- **Legacy libraries** often return untyped or loosely-typed data
- **Database results** may return strings for numeric columns
- **Configuration files** (JSON, XML, YAML, INI) parse to mixed arrays
- **External APIs** return decoded JSON with uncertain types

PHP's native type casting (`(int)`, `(string)`, etc.) is permissive and can silently produce unexpected results:

```php
(int) "42abc";     // 42 (silently ignores "abc")
(int) [];          // 0 (arrays become 0 or 1)
(bool) "false";    // true (non-empty string)
(float) "invalid"; // 0.0 (no error)
```

This library provides **validated, predictable type conversions** that throw exceptions for invalid input rather than silently corrupting data.

### Problems This Library Solves

1. **Silent data corruption**: Native casts convert invalid values without warning
2. **Inconsistent boolean handling**: PHP treats `"false"`, `"no"`, `"off"` as `true`
3. **Missing validation**: No built-in way to reject non-numeric strings for number conversion
4. **Overflow detection**: Native `(int)` silently wraps on overflow
5. **Type ambiguity**: Superglobals and legacy code return `mixed`, breaking strict typing

### Accessing Data from Superglobals

When working with `$_GET`, `$_POST`, `$_SERVER`, `$_ENV`, and other superglobals, values are always `mixed`. This library ensures type-safe access:

```php
use Ctw\Cast\Cast;

// Environment variables
$debug    = Cast::toBool($_ENV['DEBUG'] ?? 'false');
$port     = Cast::toInt($_ENV['DB_PORT'] ?? '3306');
$timeout  = Cast::toFloat($_ENV['TIMEOUT'] ?? '30.0');
$appName  = Cast::toString($_ENV['APP_NAME'] ?? '');

// GET/POST parameters
$page     = Cast::toInt($_GET['page'] ?? '1');
$limit    = Cast::toInt($_GET['limit'] ?? '10');
$active   = Cast::toBool($_POST['active'] ?? 'false');
$tags     = Cast::toArray($_POST['tags'] ?? '[]');

// Server variables
$port     = Cast::toInt($_SERVER['SERVER_PORT'] ?? '80');
$https    = Cast::toBool($_SERVER['HTTPS'] ?? 'off');

// Session data
$userId   = Cast::toInt($_SESSION['user_id'] ?? '0');
$prefs    = Cast::toArray($_SESSION['preferences'] ?? '{}');
```

### Working with Legacy Libraries

Many older PHP libraries return untyped data. This library bridges the gap between legacy code and modern strict-typed applications:

```php
use Ctw\Cast\Cast;

// Legacy database result (returns strings for all columns)
$row    = $legacyDb->fetchRow("SELECT id, price, active FROM products");
$id     = Cast::toInt($row['id']);
$price  = Cast::toFloat($row['price']);
$active = Cast::toBool($row['active']);

// Legacy configuration loader
$config     = $legacyLoader->load('app.ini');
$maxSize    = Cast::toInt($config['upload_max_size']);
$debugMode  = Cast::toBool($config['debug']);
$allowedIps = Cast::toArray($config['allowed_ips']);

// Legacy API client
$response = $legacyClient->get('/api/users');
$users    = Cast::toArray($response);
$jsonOut  = Cast::toJson($users);

// Untyped function returns
$result = some_legacy_function();
$count  = Cast::toInt($result);
```

### Where to Use This Library

- **Controllers**: Validate and cast request parameters
- **Service layers**: Ensure type safety when processing external data
- **CLI commands**: Parse command-line arguments and environment variables
- **Data mappers**: Convert database results to typed domain objects
- **API handlers**: Validate incoming JSON payloads
- **Configuration loaders**: Parse and validate config values
- **Queue workers**: Process messages with mixed payloads
- **Middleware**: Sanitize and type request/response data

### Design Goals

1. **Fail fast**: Invalid conversions throw `CastException` immediately
2. **Explicit behavior**: Every conversion rule is documented and predictable
3. **No silent corruption**: Ambiguous values are rejected, not guessed
4. **PHPStan/Psalm friendly**: Return types are precise, enabling static analysis
5. **Zero dependencies**: No external packages required

## Requirements

- PHP 8.3 or higher
- strict_types enabled

## Installation

Install by adding the package as a [Composer](https://getcomposer.org) requirement:

```bash
composer require ctw/cast
```

## Usage Examples

```php
use Ctw\Cast\Cast;

$string = Cast::toString($value);
$int    = Cast::toInt($value);
$float  = Cast::toFloat($value);
$bool   = Cast::toBool($value);
$array  = Cast::toArray($value);
$json   = Cast::toJson($value);
```

---

## `Cast::toString(mixed $value): string`

Converts values to string representation with explicit, predictable rules.

```php
Cast::toString(42);        // "42"
Cast::toString(true);      // "1"
Cast::toString(null);      // ""
```

| Input Type | Input Value            | Output                |
|------------|------------------------|-----------------------|
| string     | `"hello"`              | `"hello"`             |
| int        | `42`                   | `"42"`                |
| int        | `-17`                  | `"-17"`               |
| int        | `0`                    | `"0"`                 |
| float      | `3.14`                 | `"3.14"`              |
| float      | `-2.5`                 | `"-2.5"`              |
| float      | `1.0`                  | `"1"`                 |
| float      | `INF`                  | `"INF"`               |
| float      | `NAN`                  | `"NAN"`               |
| bool       | `true`                 | `"1"`                 |
| bool       | `false`                | `"0"`                 |
| null       | `null`                 | `""`                  |
| object     | with `__toString()`    | `__toString()` result |
| object     | without `__toString()` | CastException         |
| array      | `[1, 2, 3]`            | CastException         |
| resource   | `fopen(...)`           | CastException         |

---

## `Cast::toInt(mixed $value): int`

Converts values to integers with validation, rounding, and overflow detection.

```php
Cast::toInt("42");   // 42
Cast::toInt(3.7);    // 4 (rounded)
Cast::toInt(null);   // 0
```

| Input Type | Input Value       | Output         |
|------------|-------------------|----------------|
| int        | `42`              | `42`           |
| int        | `-17`             | `-17`          |
| bool       | `true`            | `1`            |
| bool       | `false`           | `0`            |
| null       | `null`            | `0`            |
| float      | `3.14`            | `3` (rounded)  |
| float      | `3.5`             | `4` (rounded)  |
| float      | `-2.7`            | `-3` (rounded) |
| string     | `"42"`            | `42`           |
| string     | `"  42  "`        | `42` (trimmed) |
| string     | `"3.14"`          | `3` (rounded)  |
| string     | `"1e3"`           | `1000`         |
| float      | `INF`             | CastException  |
| float      | `NAN`             | CastException  |
| float      | `1e20` (overflow) | CastException  |
| string     | `""`              | CastException  |
| string     | `"hello"`         | CastException  |
| string     | `"42abc"`         | CastException  |
| string     | out of int range  | CastException  |
| array      | `[1, 2, 3]`       | CastException  |
| object     | `stdClass`        | CastException  |
| resource   | `fopen(...)`      | CastException  |

---

## `Cast::toFloat(mixed $value): float`

Converts values to floating-point numbers with validation.

```php
Cast::toFloat("3.14");   // 3.14
Cast::toFloat(42);       // 42.0
Cast::toFloat(true);     // 1.0
```

| Input Type | Input Value  | Output           |
|------------|--------------|------------------|
| float      | `3.14`       | `3.14`           |
| float      | `-2.5`       | `-2.5`           |
| float      | `INF`        | `INF`            |
| float      | `NAN`        | `NAN`            |
| int        | `42`         | `42.0`           |
| int        | `-17`        | `-17.0`          |
| int        | `0`          | `0.0`            |
| bool       | `true`       | `1.0`            |
| bool       | `false`      | `0.0`            |
| null       | `null`       | `0.0`            |
| string     | `"3.14"`     | `3.14`           |
| string     | `"  3.14  "` | `3.14` (trimmed) |
| string     | `"42"`       | `42.0`           |
| string     | `"1e3"`      | `1000.0`         |
| string     | `"-2.5"`     | `-2.5`           |
| string     | `""`         | CastException    |
| string     | `"hello"`    | CastException    |
| string     | `"42abc"`    | CastException    |
| array      | `[1, 2, 3]`  | CastException    |
| object     | `stdClass`   | CastException    |
| resource   | `fopen(...)` | CastException    |

---

## `Cast::toBool(mixed $value): bool`

Strict boolean conversion with explicit allowed values only.

```php
Cast::toBool("yes");   // true
Cast::toBool(0);       // false
Cast::toBool(null);    // false
```

| Input Type | Input Value  | Output                             |
|------------|--------------|------------------------------------|
| bool       | `true`       | `true`                             |
| bool       | `false`      | `false`                            |
| int        | `1`          | `true`                             |
| int        | `0`          | `false`                            |
| float      | `1.0`        | `true`                             |
| float      | `0.0`        | `false`                            |
| null       | `null`       | `false`                            |
| string     | `"true"`     | `true`                             |
| string     | `"1"`        | `true`                             |
| string     | `"yes"`      | `true`                             |
| string     | `"on"`       | `true`                             |
| string     | `"y"`        | `true`                             |
| string     | `"t"`        | `true`                             |
| string     | `"false"`    | `false`                            |
| string     | `"0"`        | `false`                            |
| string     | `"no"`       | `false`                            |
| string     | `"off"`      | `false`                            |
| string     | `"n"`        | `false`                            |
| string     | `"f"`        | `false`                            |
| string     | `""`         | `false`                            |
| string     | `"  TRUE  "` | `true` (trimmed, case-insensitive) |
| int        | `2`          | CastException                      |
| int        | `-1`         | CastException                      |
| float      | `3.14`       | CastException                      |
| float      | `-1.0`       | CastException                      |
| string     | `"hello"`    | CastException                      |
| string     | `"2"`        | CastException                      |
| array      | `[1, 2, 3]`  | CastException                      |
| object     | `stdClass`   | CastException                      |
| resource   | `fopen(...)` | CastException                      |

---

## `Cast::toArray(mixed $value): array`

Intelligent array conversion with multiple strategies for different types.

```php
Cast::toArray('{"a":1}');   // ["a" => 1]
Cast::toArray(42);          // [42]
Cast::toArray(null);        // []
```

| Input Type | Input Value      | Output                               |
|------------|------------------|--------------------------------------|
| array      | `[1, 2, 3]`      | `[1, 2, 3]`                          |
| null       | `null`           | `[]`                                 |
| string     | `""`             | `[]`                                 |
| string     | `"  "`           | `[]` (trimmed)                       |
| string     | `'{"a":1}'`      | `["a" => 1]` (JSON parsed)           |
| string     | `'[1,2,3]'`      | `[1, 2, 3]` (JSON parsed)            |
| string     | `'{invalid}'`    | `['{invalid}']` (wrapped)            |
| string     | `"hello"`        | `["hello"]` (wrapped)                |
| int        | `42`             | `[42]`                               |
| float      | `3.14`           | `[3.14]`                             |
| bool       | `true`           | `[true]`                             |
| object     | `Traversable`    | `iterator_to_array()` result         |
| object     | with `toArray()` | `toArray()` result                   |
| object     | `stdClass{a:1}`  | `["a" => 1]` (via `get_object_vars`) |
| resource   | `fopen(...)`     | CastException                        |

---

## `Cast::toJson(mixed $value, int $flags = ..., int $depth = 512): string`

Type-safe JSON encoding with comprehensive validation and error handling.

```php
Cast::toJson(['name' => 'John']);   // '{"name":"John"}'
Cast::toJson(true);                 // 'true'
Cast::toJson(null);                 // 'null'
```

| Input Type | Input Value                     | Output                |
|------------|---------------------------------|-----------------------|
| null       | `null`                          | `"null"`              |
| string     | `"hello"`                       | `"\"hello\""`         |
| string     | `"with/slash"`                  | `"\"with/slash\""`    |
| int        | `42`                            | `"42"`                |
| int        | `-17`                           | `"-17"`               |
| bool       | `true`                          | `"true"`              |
| bool       | `false`                         | `"false"`             |
| float      | `3.14`                          | `"3.14"`              |
| array      | `[1, 2, 3]`                     | `"[1,2,3]"`           |
| array      | `["a" => 1]`                    | `"{\"a\":1}"`         |
| object     | `JsonSerializable`              | via `jsonSerialize()` |
| object     | with `toArray()`                | via `toArray()`       |
| object     | `stdClass{a:1}`                 | `"{\"a\":1}"`         |
| float      | `INF`                           | CastException         |
| float      | `NAN`                           | CastException         |
| (any)      | depth < 1                       | CastException         |
| object     | `toArray()` not returning array | CastException         |
| resource   | `fopen(...)`                    | CastException         |

**Default flags:** `JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE`

---

## Error Handling

All methods throw `CastException` for invalid inputs:

```php
use Ctw\Cast\Cast;
use Ctw\Cast\Exception\CastException;

try {
    $result = Cast::toInt($userInput);
} catch (CastException $e) {
    error_log($e->getMessage());
}
```
