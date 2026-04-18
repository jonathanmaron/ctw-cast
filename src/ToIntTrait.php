<?php
declare(strict_types=1);

namespace Ctw\Cast;

/**
 * Trait providing integer conversion functionality.
 */
trait ToIntTrait
{
    /**
     * Casts a value to integer.
     *
     * Converts the input value to an integer with rounding for floats.
     * Values that cannot be interpreted as integers (invalid strings, overflow,
     * infinite/NaN floats, arrays, objects, resources) return 0.
     *
     * Conversion Rules:
     * -----------------
     * | Input Type | Input Example          | Output         |
     * |------------|------------------------|----------------|
     * | int        | 42                     | 42             |
     * | int        | -17                    | -17            |
     * | bool       | true                   | 1              |
     * | bool       | false                  | 0              |
     * | null       | null                   | 0              |
     * | float      | 3.14                   | 3 (rounded)    |
     * | float      | 3.5                    | 4 (rounded)    |
     * | float      | -2.7                   | -3 (rounded)   |
     * | string     | "42"                   | 42             |
     * | string     | "  42  "               | 42 (trimmed)   |
     * | string     | "3.14"                 | 3 (rounded)    |
     * | string     | "1e3"                  | 1000           |
     * | float      | INF                    | 0              |
     * | float      | NAN                    | 0              |
     * | float      | 1e20 (overflow)        | 0              |
     * | string     | ""                     | 0              |
     * | string     | "hello"                | 0              |
     * | string     | "42abc"                | 0              |
     * | array      | [1, 2, 3]              | 0              |
     * | object     | stdClass               | 0              |
     * | resource   | fopen(...)             | 0              |
     *
     * Rounding Behavior:
     * ------------------
     * Floats are rounded using PHP's round() function (standard rounding):
     * - 3.4 → 3, 3.5 → 4, 3.6 → 4
     * - -3.4 → -3, -3.5 → -4, -3.6 → -4
     *
     * Overflow Detection:
     * -------------------
     * Values outside PHP_INT_MIN to PHP_INT_MAX range return 0.
     *
     * @param mixed $value The value to convert
     *
     * @return int The cast integer, or 0 if the value cannot be cast
     */
    public static function toInt(mixed $value): int
    {
        if (is_int($value)) {
            return $value;
        }

        if (is_bool($value)) {
            return $value ? self::INT_TRUE : self::EMPTY_INT;
        }

        if (is_string($value)) {
            $trimmed = trim($value);
            if (self::EMPTY_STRING === $trimmed) {
                return self::EMPTY_INT;
            }
            if (!is_numeric($trimmed)) {
                return self::EMPTY_INT;
            }
            $numericValue = $trimmed + 0;
            if (is_float($numericValue)) {
                if (PHP_INT_MAX < $numericValue || PHP_INT_MIN > $numericValue) {
                    return self::EMPTY_INT;
                }

                return (int) round($numericValue);
            }

            return $numericValue;
        }

        if (is_float($value)) {
            if (is_infinite($value) || is_nan($value)) {
                return self::EMPTY_INT;
            }
            if (PHP_INT_MAX < $value || PHP_INT_MIN > $value) {
                return self::EMPTY_INT;
            }

            return (int) round($value);
        }

        return self::EMPTY_INT;
    }
}
