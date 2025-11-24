# Package "ctw/ctw-cast"

Type-safe casting utility for PHP 8.3+ applications with comprehensive error handling.

## Why This Library Exists

PHP's native type casting is permissive and can produce unexpected results. When you cast `(string) $value`, PHP will attempt to convert almost anything—often silently failing, throwing a TypeError or producing nonsensical output. This becomes especially problematic when:


1. **Interfacing with legacy non-typed libraries** that return `mixed` types without proper type declarations
2. **Accessing request data** from `$_GET`, `$_POST`, `$_SERVER`, or framework request objects where all values are mixed
3. **Working with external APIs** that return loosely-typed data structures
4. **Using PHPStan 2** or other strict static analysis tools that require explicit type conversions

In modern PHP 8.3+ applications with strict type checking, you need a reliable way to safely convert these untyped values into the specific types your application requires.

**The problem with native casting:**

```php
(string) null;           // "" - silent conversion loses information
(int) "not a number";    // 0 - silent failure
(bool) 2;                // true - unclear intent
(array) 42;              // [42] - non-intuitive behavior
```

**The CTW Cast solution:**

```php
use Ctw\Cast\Cast;
use Ctw\Cast\Exception\CastException;

Cast::toString(null);         // "" - explicit, documented behavior
Cast::toInt("not a number");  // CastException with clear message
Cast::toBool(2);              // CastException - only 0 and 1 allowed
Cast::toArray(42);            // [42] - same result, but explicit intent
Cast::toJson(['foo' => 'bar']); // {"foo":"bar"} - type-safe JSON encoding
```

This library provides a static `Cast` class with six type conversion methods that handle edge cases properly, throw descriptive exceptions for invalid inputs, and work seamlessly with PHPStan 2's strict type checking.

## Common Use Cases

### Working with Request Data

```php
use Ctw\Cast\Cast;
use Ctw\Cast\Exception\CastException;

// Safely handle GET/POST parameters
try {
    $userId = Cast::toInt($_GET['user_id'] ?? null);     // Ensures integer or throws
    $page = Cast::toInt($_GET['page'] ?? 1);             // Default to 1 if missing
    $isActive = Cast::toBool($_POST['active'] ?? null);  // Strict bool conversion
    $tags = Cast::toArray($_POST['tags'] ?? []);         // Ensures array type
} catch (CastException $e) {
    // Handle invalid input with descriptive error message
    return new ErrorResponse($e->getMessage(), 400);
}
```

### Interfacing with Legacy Libraries

```php
use Ctw\Cast\Cast;

// Legacy library returns mixed types
$legacyDb = new OldDatabaseLibrary();
$row = $legacyDb->fetchRow(); // Returns array<string, mixed>

// Safely cast to required types
$accountId = Cast::toInt($row['account_id']);        // Guaranteed int or exception
$email = Cast::toString($row['email']);              // Guaranteed string
$balance = Cast::toFloat($row['balance']);           // Guaranteed float
$isVerified = Cast::toBool($row['is_verified']);     // Strict bool (0/1 only)
$metadata = Cast::toArray($row['metadata'] ?? '[]'); // Handles JSON strings
```

### Handling Environment Variables

```php
use Ctw\Cast\Cast;

// Environment variables are always strings
$config = [
    'debug' => Cast::toBool(getenv('APP_DEBUG')),           // "true"/"1" -> true
    'port' => Cast::toInt(getenv('APP_PORT')),              // "8080" -> 8080
    'timeout' => Cast::toFloat(getenv('REQUEST_TIMEOUT')),  // "30.5" -> 30.5
    'allowed_ips' => Cast::toArray(getenv('ALLOWED_IPS')),  // JSON string -> array
];
```

## Installation

```bash
composer require ctw/cast
```

## Usage

```php
use Ctw\Cast\Cast;

$string = Cast::toString($value);
$int = Cast::toInt($value);
$float = Cast::toFloat($value);
$bool = Cast::toBool($value);
$array = Cast::toArray($value);
$json = Cast::toJson($value);
```

## Methods

### `Cast::toString(mixed $value): string`

Converts any value to a string representation with clear rules.

**Supported types:**
- `string` → returned as-is
- `int`, `float` → converted to string representation
- `bool` → `true` becomes `"1"`, `false` becomes `"0"`
- `null` → returns empty string `""`
- `object` → only if it implements `__toString()`, otherwise throws exception
- `array`, `resource` → throws exception

**Example:**

```php
Cast::toString(42);              // "42"
Cast::toString(3.14);            // "3.14"
Cast::toString(true);            // "1"
Cast::toString(false);           // "0"
Cast::toString(null);            // ""

$obj = new class {
    public function __toString(): string {
        return 'custom';
    }
};
Cast::toString($obj);            // "custom"

Cast::toString([1, 2, 3]);       // CastException
```

### `Cast::toInt(mixed $value): int`

Converts values to integers with validation and overflow detection.

**Features:**
- Rounds floats using standard rounding (3.14 → 3, 3.7 → 4)
- Validates numeric strings with whitespace trimming
- Detects integer overflow (values outside `PHP_INT_MIN`/`PHP_INT_MAX`)
- Rejects `NAN` and `INF` with clear error messages

**Example:**

```php
Cast::toInt(42);                 // 42
Cast::toInt(3.14);               // 3 (rounded)
Cast::toInt(3.7);                // 4 (rounded)
Cast::toInt("42");               // 42
Cast::toInt("  42  ");           // 42 (whitespace trimmed)
Cast::toInt("3.14");             // 3 (parsed as float, then rounded)
Cast::toInt(true);               // 1
Cast::toInt(false);              // 0
Cast::toInt(null);               // 0

Cast::toInt(NAN);                // CastException: Float value NaN (infinite or NaN) cannot be converted to int
Cast::toInt(INF);                // CastException: Float value INF (infinite or NaN) cannot be converted to int
Cast::toInt("not a number");     // CastException: String value "not a number" is not numeric and cannot be converted to int
```

### `Cast::toFloat(mixed $value): float`

Converts values to floating-point numbers.

**Example:**

```php
Cast::toFloat(42);               // 42.0
Cast::toFloat(3.14);             // 3.14
Cast::toFloat("3.14");           // 3.14
Cast::toFloat("  3.14  ");       // 3.14 (whitespace trimmed)
Cast::toFloat(true);             // 1.0
Cast::toFloat(false);            // 0.0
Cast::toFloat(null);             // 0.0

Cast::toFloat("not a number");   // CastException: String value "not a number" is not numeric and cannot be converted to float
```

### `Cast::toBool(mixed $value): bool`

Strict boolean conversion with explicit allowed values.

**Rules:**
- Integers: **only** `0` → `false`, `1` → `true` (rejects 2, -1, etc.)
- Floats: **only** `0.0` → `false`, `1.0` → `true`
- Strings (case-insensitive):
  - `true`: "true", "yes", "on", "y", "t", "1"
  - `false`: "false", "no", "off", "n", "f", "0", "" (empty string)
- Booleans: returned as-is
- Everything else throws an exception

**Example:**

```php
Cast::toBool(true);              // true
Cast::toBool(false);             // false
Cast::toBool(1);                 // true
Cast::toBool(0);                 // false
Cast::toBool("yes");             // true
Cast::toBool("no");              // false
Cast::toBool("on");              // true
Cast::toBool("off");             // false
Cast::toBool("");                // false
Cast::toBool(null);              // false

Cast::toBool(2);                 // CastException: Integer value 2 cannot be converted to bool (only 0 and 1 are accepted)
Cast::toBool(-1);                // CastException: Integer value -1 cannot be converted to bool (only 0 and 1 are accepted)
Cast::toBool("maybe");           // CastException: String value "maybe" cannot be converted to bool
```

### `Cast::toArray(mixed $value): array`

Intelligent array conversion with multiple strategies.

**Conversion strategies (in order):**
1. Arrays → returned as-is
2. `null` → returns empty array `[]`
3. Strings:
   - Empty strings → returns empty array `[]`
   - Strings starting with `{` or `[` → attempt JSON parsing, fall back to wrapping
   - Other strings → wrapped in single-element array
4. Objects:
   - `Traversable` objects → converted via `iterator_to_array()`
   - Objects with `toArray()` method → calls the method
   - Generic objects → converts via `get_object_vars()`
5. Scalars (int, float, bool) → wrapped in single-element array
6. Everything else throws an exception

**Example:**

```php
Cast::toArray([1, 2, 3]);                    // [1, 2, 3]
Cast::toArray('{"key":"value"}');            // ['key' => 'value'] (JSON parsed)
Cast::toArray('[1,2,3]');                    // [1, 2, 3] (JSON parsed)
Cast::toArray(42);                           // [42]
Cast::toArray("hello");                      // ["hello"]
Cast::toArray("");                           // []
Cast::toArray(null);                         // []

$obj = new stdClass();
$obj->name = 'John';
Cast::toArray($obj);                         // ['name' => 'John']

$traversable = new ArrayIterator([1, 2, 3]);
Cast::toArray($traversable);                 // [1, 2, 3]
```

### `Cast::toJson(mixed $value, int $flags = JSON_THROW_ON_ERROR | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE, int $depth = 512): string`

Type-safe JSON encoding with comprehensive validation and error handling.

**Features:**
- Supports all JSON-compatible types: null, strings, integers, floats, booleans, arrays, and objects
- Rejects non-JSON-compatible values like `NAN` and `INF` with clear error messages
- Handles objects via `JsonSerializable`, `toArray()` method, or `get_object_vars()`
- Default flags preserve Unicode and don't escape slashes for cleaner output
- Customizable JSON encoding flags and depth limits
- Always throws descriptive exceptions on encoding failures

**Supported types:**
- `null` → `"null"` (JSON null)
- `string` → JSON-encoded string with proper escaping
- `int`, `float` → JSON number (rejects `NAN` and `INF`)
- `bool` → `"true"` or `"false"`
- `array` → JSON array or object depending on keys
- `object` → JSON object via serialization strategy

**Example:**

```php
// Basic types
Cast::toJson(null);                          // "null"
Cast::toJson("hello");                       // "\"hello\""
Cast::toJson(42);                            // "42"
Cast::toJson(3.14);                          // "3.14"
Cast::toJson(true);                          // "true"
Cast::toJson(false);                         // "false"

// Arrays
Cast::toJson([]);                            // "[]"
Cast::toJson([1, 2, 3]);                     // "[1,2,3]"
Cast::toJson(['name' => 'John', 'age' => 30]); // "{\"name\":\"John\",\"age\":30}"

// Complex nested structures
Cast::toJson([
    'user' => [
        'name' => 'John',
        'hobbies' => ['reading', 'coding']
    ],
    'active' => true
]);
// {"user":{"name":"John","hobbies":["reading","coding"]},"active":true}

// Objects
$obj = new stdClass();
$obj->name = 'John';
Cast::toJson($obj);                          // "{\"name\":\"John\"}"

// JsonSerializable objects
class User implements JsonSerializable {
    public function jsonSerialize(): array {
        return ['name' => 'John', 'role' => 'admin'];
    }
}
Cast::toJson(new User());                    // "{\"name\":\"John\",\"role\":\"admin\"}"

// Objects with toArray() method
class Product {
    public function toArray(): array {
        return ['id' => 123, 'name' => 'Widget'];
    }
}
Cast::toJson(new Product());                 // "{\"id\":123,\"name\":\"Widget\"}"

// Custom flags for pretty printing
Cast::toJson(
    ['name' => 'John', 'age' => 30],
    JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT
);
// {
//     "name": "John",
//     "age": 30
// }

// Error handling
Cast::toJson(NAN);                           // CastException: Float value NAN cannot be converted to JSON
Cast::toJson(INF);                           // CastException: Float value INF cannot be converted to JSON

// Unicode support (default flags preserve unicode)
Cast::toJson('Hello 世界 🌍');               // "\"Hello 世界 🌍\""

// URL encoding (default flags don't escape slashes)
Cast::toJson(['url' => 'https://example.com/path']);
// {"url":"https://example.com/path"}
```

**Common use cases:**

```php
// API response formatting
$response = Cast::toJson([
    'status' => 'success',
    'data' => $records,
    'timestamp' => time()
]);
header('Content-Type: application/json');
echo $response;

// Logging structured data
logger()->info('User action', [
    'action' => 'login',
    'payload' => Cast::toJson($userData)
]);

// Database storage of JSON columns
$stmt->execute([
    'settings' => Cast::toJson($userSettings),
    'metadata' => Cast::toJson($metadata)
]);

// Configuration serialization
file_put_contents(
    'config.json',
    Cast::toJson($config, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT)
);
```

## Why This Matters for PHPStan 2

PHPStan 2 introduces stricter type checking and requires explicit type conversions that maintain type safety throughout your application. Native PHP casting operators `(string)`, `(int)`, etc., are flagged by PHPStan as potentially unsafe because they can produce unexpected results.

**PHPStan 2 issues with native casting:**

```php
function processData(mixed $input): string {
    return (string) $input; // ❌ PHPStan error: unsafe cast
}
```

**Clean solution with CTW Cast:**

```php
use Ctw\Cast\Cast;

function processData(mixed $input): string {
    return Cast::toString($input); // ✅ PHPStan level 9 compliant
}
```

The CTW Cast methods are designed to work seamlessly with PHPStan's strictest analysis level (level 9), providing:

- **Explicit type guarantees**: Each method's return type is guaranteed
- **Documented error conditions**: PHPStan understands when exceptions are thrown
- **Clear conversion rules**: No ambiguous or implicit behavior
- **Edge case handling**: NaN, Infinity, overflow, and other edge cases are handled explicitly

## Error Handling

All methods throw `CastException` with descriptive error messages when conversion fails:

```php
use Ctw\Cast\Cast;
use Ctw\Cast\Exception\CastException;

try {
    $result = Cast::toInt($userInput);
} catch (CastException $e) {
    // Handle invalid input
    error_log($e->getMessage());
}
```

## Design Principles

1. **Fail explicitly, never silently**: Invalid conversions throw exceptions rather than returning unexpected values
2. **Predictable behavior**: Each method has clearly documented rules—no surprises
3. **Type safety first**: Designed for strict static analysis and runtime safety
4. **Developer-friendly errors**: Error messages include the actual type received
5. **Zero dependencies**: Pure PHP 8.3+ implementation

## Requirements

- PHP 8.3 or higher
- Strict types enabled (`declare(strict_types=1)`)

## Testing

The library includes comprehensive PHPUnit tests covering all edge cases:

```bash
composer test
```

## Contributing

Contributions are welcome! Please ensure all tests pass and maintain PHPStan level 9 compliance.

```bash
composer qa        # Run all QA tools
composer qa-fix    # Fix code style issues
composer test      # Run tests
```
