# PHP 8.5.7 Migration — `ctw/ctw-cast`

- **Branch:** `php85` (cut from `master`)
- **Runtime:** PHP 8.3.31 → **8.5.7**
- **PHPUnit:** 12 → **13.2.1**
- **Status:** ✅ done

## Audit checklist

### `src/ToStringTrait.php`

- [x] **(warning) `src/ToStringTrait.php`** — casting a non-finite float
  (`NAN` / `INF` / `-INF`) with `(string) $value` triggers PHP 8.5's
  `unexpected NAN value was coerced to string` `E_WARNING`.
  - **Fix:** guarded non-finite floats with `is_finite()` / `is_nan()`, returning
    the documented `'NAN'` / `'INF'` / `'-INF'` strings before any coercion
    (commit "🐛 fix: Guarded non-finite floats in Cast::toString for PHP 8.5").

### Tooling / docs

- [x] **(tooling) `ecs.php`** — ECS config migrated to the `ECSConfig::configure()`
  fluent API.
  - **Fix:** rewrote the config closure using `ECSConfig::configure()->…`.
- [x] **(tooling) `README.md`** — documented the PHP 8.5 minimum requirement.
  - **Fix:** updated the requirements section to PHP 8.5.

## composer.json & CI

- [x] **`require.php`** — `^8.3` → **`^8.5`**.
- [x] **`phpunit/phpunit`** — `^12.0` → **`^13.0`** (installs 13.2.1).
- [x] **`ctw/ctw-qa`** — pinned to **`dev-php85`** (inherits the shared PHPStan
  `reportUnmatchedIgnoredErrors: false` fix).
- [x] **`.github/workflows/tests.yml`** — CI matrix pinned to PHP **`8.5`** only.

## Final audit (PHP 8.5.7)

- [x] **`php -v`** — PHP **8.5.7** (cli).
- [x] **`composer update -W`** — clean; no dependency blocked by the PHP 8.5
  platform requirement.
- [x] **PHPUnit** — **291 tests, 329 assertions**, no issues (PHPUnit 13.2.1); no
  NAN/INF coercion warnings remain.
- [x] **PHPStan** — `[OK] No errors` (level max).

```bash
php -v                                  # PHP 8.5.7
composer update -W                      # clean
php vendor/bin/phpunit --no-coverage    # OK (291 tests, 329 assertions)
composer phpstan                        # No issues found
```
