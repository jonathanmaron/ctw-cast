# CTW Cast Library - Development Guide

## Project Overview

The CTW Cast Library provides type-safe casting utility for PHP 8.3 applications. It implements a `Cast` class with five static conversion methods (`Cast::toString()`, `Cast::toInt()`, `Cast::toFloat()`, `Cast::toBool()`, `Cast::toArray()`) that handle edge cases, provide descriptive error messages, and ensure consistent type conversion across applications.

## Architecture

- **Static class design**: Single `Cast` class with five static methods
- **Namespace**: `Ctw\Cast\Cast`
- **Comprehensive error handling**: All methods throw `CastException` with descriptive messages
- **Edge case handling**: Proper handling of NaN, Infinity, overflow, JSON parsing, object conversion, etc.
- **Comprehensive testing**: Each method has extensive test coverage with edge cases

## Development Commands

### Quality Assurance

```bash
composer qa                # Run all QA tools (rector, ecs, phpstan)
composer qa-fix            # Run QA tools with fixes applied
```

### Code Style

```bash
composer ecs               # Check code style
composer ecs-fix           # Fix code style issues
```

### Static Analysis

```bash
composer phpstan           # Run PHPStan static analysis
composer phpstan-baseline  # Generate PHPStan baseline
```

### Code Refactoring

```bash
composer rector             # Check for refactoring opportunities (dry-run)
composer rector-fix         # Apply refactoring changes
```

### Testing

```bash
composer test         # Run PHPUnit tests
vendor/bin/phpunit test/SpecificTest.php  # Run specific test file
```

## File Structure

```
incubator/ctw/ctw-cast/
├── src/
│   ├── Cast.php               # Cast class with all static methods
│   └── Exception/
│       └── CastException.php  # Custom exception for cast failures
├── test/
│   └── CastTest.php           # Tests for Cast class methods
├── composer.json
├── phpunit.xml.dist
├── ecs.php
├── phpstan.neon
├── phpstan-baseline.neon
├── rector.php
├── README.md
└── CLAUDE.md
```

## Method Implementations

### Core Principles

All methods follow these principles:

1. **Type validation first**: Check if input is already the target type
2. **Handle null explicitly**: Defined behavior for null values (converts to sensible defaults)
3. **Descriptive error messages**: Use sprintf with get_debug_type()
4. **Clear control flow**: Simple if statements for readability
5. **Edge case handling**: NaN, Infinity, overflow, whitespace, JSON, etc.

### Cast::toString(mixed $value): string

Converts any value to string representation.

**Design decisions:**
- Uses simple if statements for clear control flow
- Booleans: true → '1', false → '0' (consistent with PHP's string casting)
- Null: Returns empty string ''
- Objects: Only converts if __toString() exists
- Uses get_debug_type() for error messages

### Cast::toInt(mixed $value): int

Converts to integer with proper validation.

**Design decisions:**
- Null: Returns 0
- Rounds floats instead of truncating (more mathematically correct)
- Detects overflow (values outside PHP_INT_MIN/PHP_INT_MAX)
- Rejects NaN and Infinity with clear messages
- Trims whitespace from string inputs
- Handles numeric strings properly (e.g., "42", "3.14")

### Cast::toFloat(mixed $value): float

Converts to float representation.

**Design decisions:**
- Uses simple if statements for clear control flow
- Null: Returns 0.0
- Simple and straightforward conversion
- Trims whitespace from string inputs
- Validates numeric strings

### Cast::toBool(mixed $value): bool

Strict boolean conversion.

**Design decisions:**
- Null: Returns false
- Integers: **Only** 0 → false, 1 → true (rejects 2, -1, etc.) using match expressions
- Floats: **Only** 0.0 → false, 1.0 → true using match expressions
- Strings: Accepts multiple formats (true/false, yes/no, on/off, y/n, t/f, 1/0)
- Empty string → false
- Case-insensitive string matching

### Cast::toArray(mixed $value): array

Intelligent array conversion.

**Design decisions:**
- Null: Returns empty array []
- Attempts JSON parsing for strings starting with '{' or '['
- Handles Traversable objects via iterator_to_array()
- Calls toArray() method if available on objects
- Falls back to get_object_vars() for generic objects
- Wraps scalars in single-element arrays
- Empty string → empty array

## Testing Guidelines

- Test all supported input types for each method
- Test edge cases: null, empty strings, whitespace, special values (NaN, Infinity)
- Test error conditions with assertException
- Test boundary values (PHP_INT_MAX, PHP_INT_MIN, etc.)
- Test object conversions (__toString, toArray, Traversable, stdClass)
- Test JSON parsing for Cast::toArray()
- Ensure comprehensive coverage

## Code Quality Standards

- **PHPStan Level 9**: Maximum static analysis level
- **ECS**: Easy Coding Standard compliance
- **Rector**: Modern PHP refactoring standards
- **100% test coverage**: Comprehensive PHPUnit test suite
- **Strict types**: All files use `declare(strict_types=1)`

## PHP 8.3 Standards

- Full PHP 8.3 compatibility
- Strict type enforcement through `declare(strict_types=1)`
- Comprehensive type declarations for all functions
- Yoda condition formatting for all conditional statements
- Modern features: match expressions, null coalescing operators, named arguments
- Comprehensive PHPDoc documentation
- Production-ready, optimized code

## Usage

The Cast class is autoloaded via PSR-4 in the `Ctw\Cast` namespace:

```json
"autoload": {
    "psr-4": {
        "Ctw\\Cast\\": "src/"
    }
}
```

Usage:

```php
use Ctw\Cast\Cast;

$str = Cast::toString($value);
$int = Cast::toInt($value);
$float = Cast::toFloat($value);
$bool = Cast::toBool($value);
$array = Cast::toArray($value);
```

## Common Patterns

### Error Message Formatting

```php
use Ctw\Cast\Exception\CastException;

throw new CastException(
    sprintf('Value of type %s cannot be converted.', get_debug_type($value))
);
```

### Type Checking with If Statements

```php
use Ctw\Cast\Exception\CastException;

if (is_string($value)) {
    return $value;
}

if (is_int($value) || is_float($value)) {
    return (string) $value;
}

// ... more checks

throw new CastException(
    sprintf('Value of type %s cannot be converted.', get_debug_type($value))
);
```

### Bounds Checking

```php
use Ctw\Cast\Exception\CastException;

if (PHP_INT_MAX < $value || PHP_INT_MIN > $value) {
    throw new CastException(
        sprintf('Value %s is out of integer range.', $value)
    );
}
```
