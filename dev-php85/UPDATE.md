# PHP 8.5.7 Upgrade — `ctw/ctw-cast`

- **Branch:** `php85` (cut from `master`)
- **Runtime:** PHP 8.3.31 → **8.5.7**
- **Date:** 2026-06-25

This is a **TODO list** of the changes required for this package to run cleanly
under PHP 8.5.7. Nothing here has been fixed yet — the fixes happen in a second
step. Boxes are intentionally left unchecked.

Detection commands used:

```bash
composer update -W
php vendor/bin/phpunit --no-coverage --display-deprecations --display-warnings --display-notices --display-errors
composer rector      # rector --dry-run
composer phpstan
```

---

## 1. `composer update -W`

✅ **Succeeded.** No dependency was blocked by an incompatible PHP 8.5 platform
requirement; every package resolved a version that allows PHP 8.5.7.

Notable upgrades (dev only — this package has no runtime deps beyond `php`):

| Package | From | To |
| --- | --- | --- |
| `ctw/ctw-qa` | 5.0.11 | 5.0.15 |
| `phpunit/phpunit` | 12.4.4 | 12.5.30 |
| `phpstan/phpstan` | 2.1.32 | 2.2.2 |
| `rector/rector` | 2.2.8 | 2.5.2 |
| `symplify/easy-coding-standard` | 13.0.0 | 13.2.3 |
| `symfony/var-dumper` | 7.3.5 | 7.4.8 |

`composer.lock` is git-ignored in this package, so the update produces no
committed diff — only this report is committed on the `php85` branch.

---

## 2. PHP 8.5 runtime issues (must fix)

- [ ] **`src/ToStringTrait.php:52`** — `Cast::toString()` does
  `return (string) $value;` for floats. Under PHP 8.5 casting `NAN` (and `INF`)
  to string raises a new `E_WARNING`:
  `unexpected NAN value was coerced to string`.
  **Fix:** special-case non-finite floats before the cast, e.g.
  `if (is_float($value) && !is_finite($value)) { return $value !== $value ? 'NAN' : ($value > 0 ? 'INF' : '-INF'); }`
  (or whatever string the documented behavior table requires — the docblock
  already promises `NAN → "NAN"` and `INF → "INF"`).
  **Surfaced by:** `CtwTest\Cast\ToStringTest::testToStringConvertsNaNToString`
  (`test/ToStringTest.php:314`).

---

## 3. QA tooling issues (surfaced by the dependency update)

- [ ] **`ecs.php:26`** — PHPStan `ignore.unmatchedLine`: the ignore patterns
  `missingType.generics` / `missingType.iterableValue` no longer match a
  reported error after the `phpstan/phpstan` 2.1 → 2.2 upgrade. Remove the now
  obsolete `@phpstan-ignore` line (or the corresponding baseline/ignore entry).

---

## 4. Notes (non-blocking, not PHP 8.5 specific)

- Running `php vendor/bin/phpunit` with the project config reports
  **"No tests executed!"** on this machine because `phpunit.xml.dist` configures
  a `<coverage>` report but no coverage driver (Xdebug/PCOV) is installed
  locally. This is environmental — CI has a driver. Use `--no-coverage` to run
  the suite locally. Not a PHP 8.5 regression.

---

## 5. Verification snapshot (current state on `php85`)

| Check | Result |
| --- | --- |
| `composer update -W` | ✅ clean |
| PHPUnit (`--no-coverage`) | 285 tests, 317 assertions, **1 warning** (the NAN cast above) |
| Rector (dry-run) | ✅ no changes proposed |
| PHPStan | ❌ 1 issue (`ecs.php:26`, see §3) |

Once §2 and §3 are addressed the package should be green under PHP 8.5.7.
