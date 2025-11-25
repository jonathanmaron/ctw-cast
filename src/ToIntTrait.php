<?php
declare(strict_types=1);

namespace Ctw\Cast;

/**
 * Trait providing integer conversion functionality.
 */
trait ToIntTrait
{
    private const string ERR_EMPTY_STRING_TO_INT       = 'Empty string cannot be cast to int.';

    private const string ERR_NON_NUMERIC_STRING_TO_INT = 'String value "%s" is not numeric and cannot be cast to int.';

    private const string ERR_STRING_OUT_OF_RANGE       = 'Numeric string value "%s" is out of integer range.';

    private const string ERR_FLOAT_INFINITE_OR_NAN     = 'Float value %s (infinite or NaN) cannot be cast to int.';

    private const string ERR_FLOAT_OUT_OF_RANGE        = 'Float value %s is out of integer range (min: %d, max: %d).';

    private const string ERR_CANNOT_CAST_TO_INT        = 'Value of type %s cannot be cast to int.';

    /**
     * Casts a value to integer.
     *
     * Converts the input value to an integer with validation, rounding, and
     * overflow detection. Unlike PHP's native (int) cast, this method validates
     * input, rounds floats properly, and throws exceptions for invalid values.
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
     * | float      | INF                    | CastException  |
     * | float      | NAN                    | CastException  |
     * | float      | 1e20 (overflow)        | CastException  |
     * | string     | ""                     | CastException  |
     * | string     | "hello"                | CastException  |
     * | string     | "42abc"                | CastException  |
     * | array      | [1, 2, 3]              | CastException  |
     * | object     | stdClass               | CastException  |
     * | resource   | fopen(...)             | CastException  |
     *
     * Rounding Behavior:
     * ------------------
     * Floats are rounded using PHP's round() function (standard rounding):
     * - 3.4 → 3, 3.5 → 4, 3.6 → 4
     * - -3.4 → -3, -3.5 → -4, -3.6 → -4
     *
     * Overflow Detection:
     * -------------------
     * Values outside PHP_INT_MIN to PHP_INT_MAX range throw CastException.
     *
     * @param mixed $value The value to convert
     *
     * @return int The cast integer
     */
    public static function toInt(mixed $value): int
    {
        if (is_int($value)) {
            return $value;
        }

        if (is_bool($value)) {
            return $value ? self::INT_TRUE : self::INT_FALSE;
        }

        if (is_string($value)) {
            $trimmed = trim($value);
            if (self::EMPTY_STRING === $trimmed) {
                self::throwCastException(self::ERR_EMPTY_STRING_TO_INT);
            }
            if (!is_numeric($trimmed)) {
                self::throwCastException(self::ERR_NON_NUMERIC_STRING_TO_INT, $trimmed);
            }
            $numericValue = $trimmed + 0;
            if (is_float($numericValue)) {
                if (PHP_INT_MAX < $numericValue || PHP_INT_MIN > $numericValue) {
                    self::throwCastException(self::ERR_STRING_OUT_OF_RANGE, $trimmed);
                }

                return (int) round($numericValue);
            }

            return $numericValue;
        }

        if (is_float($value)) {
            if (is_infinite($value) || is_nan($value)) {
                self::throwCastException(self::ERR_FLOAT_INFINITE_OR_NAN, $value);
            }
            if (PHP_INT_MAX < $value || PHP_INT_MIN > $value) {
                self::throwCastException(self::ERR_FLOAT_OUT_OF_RANGE, $value, PHP_INT_MIN, PHP_INT_MAX);
            }

            return (int) round($value);
        }

        if (null === $value) {
            return self::INT_FALSE;
        }

        self::throwCastException(self::ERR_CANNOT_CAST_TO_INT, $value);
    }
}
