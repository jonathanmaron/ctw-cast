# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [2.0.0] - 2026-04-18

This release removes all exception-based error handling. `Cast::to*` methods
now return a documented safe default when a value cannot be converted to the
target type, making the library suitable for hot paths, middleware, and
defensive code where unexpected input must never interrupt execution.

### Changed

- **BREAKING:** `Cast::toArray()`, `Cast::toBool()`, `Cast::toFloat()`,
  `Cast::toInt()`, `Cast::toJson()`, and `Cast::toString()` no longer throw
  exceptions. When a value cannot be cast they return the following defaults:

  | Method     | Default Return |
  |------------|----------------|
  | `toArray`  | `[]`           |
  | `toBool`   | `false`        |
  | `toFloat`  | `0.0`          |
  | `toInt`    | `0`            |
  | `toJson`   | `'{}'`         |
  | `toString` | `''`           |

- Default return values are centralised as `EMPTY_ARRAY`, `EMPTY_BOOL`,
  `EMPTY_FLOAT`, `EMPTY_INT`, `EMPTY_JSON`, and `EMPTY_STRING` class
  constants on `Cast`.
- Rewrote the README "Error Handling" section to document the
  exception-free contract; `try`/`catch` blocks around cast calls are no
  longer required.

### Removed

- **BREAKING:** `Ctw\Cast\Exception\CastException` class. Callers that
  previously caught `CastException` should instead check the returned
  default value.
- **BREAKING:** The `src/Exception/` namespace.

### Migration from 1.x

Code that relied on exceptions to detect invalid input must be updated to
inspect the returned value instead:

```php
// 1.x
try {
    $port = Cast::toInt($_ENV['PORT']);
} catch (CastException $e) {
    $port = 3306;
}

// 2.x
$port = Cast::toInt($_ENV['PORT']);
if (0 === $port) {
    $port = 3306;
}

// or, equivalently, using the null coalescing operator on the input
$port = Cast::toInt($_ENV['PORT'] ?? '3306');
```

## [1.0.1] - 2025-11-25

### Changed

- Internal refactoring; no behavioral changes.

## [1.0.0] - 2025-11-25

### Added

- Initial release of `ctw/ctw-cast`.
- `Cast::toArray()`, `Cast::toBool()`, `Cast::toFloat()`, `Cast::toInt()`,
  `Cast::toJson()`, and `Cast::toString()` methods for type-safe casting
  in PHP 8.3+.
- `Ctw\Cast\Exception\CastException` thrown on unsupported conversions.

[2.0.0]: https://github.com/jonathanmaron/ctw-cast/compare/release-1.0.1...release-2.0.0
[1.0.1]: https://github.com/jonathanmaron/ctw-cast/compare/release-1.0.0...release-1.0.1
[1.0.0]: https://github.com/jonathanmaron/ctw-cast/releases/tag/release-1.0.0
