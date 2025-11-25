<?php
declare(strict_types=1);

namespace Ctw\Cast;

/**
 * Trait providing float conversion functionality.
 */
trait ToFloatTrait
{
    private const string ERR_EMPTY_STRING_TO_FLOAT       = 'Empty string cannot be cast to float.';

    private const string ERR_NON_NUMERIC_STRING_TO_FLOAT = 'String value "%s" is not numeric and cannot be cast to float.';

    private const string ERR_CANNOT_CAST_TO_FLOAT        = 'Value of type %s cannot be cast to float.';

    /**
     * Casts a value to float.
     *
     * Converts the input value to a float with validation. Unlike PHP's native
     * (float) cast, this method validates input and throws exceptions for
     * invalid or non-convertible values.
     *
     * Conversion Rules:
     * -----------------
     * | Input Type | Input Example          | Output         |
     * |------------|------------------------|----------------|
     * | float      | 3.14                   | 3.14           |
     * | float      | -2.5                   | -2.5           |
     * | float      | INF                    | INF            |
     * | float      | NAN                    | NAN            |
     * | int        | 42                     | 42.0           |
     * | int        | -17                    | -17.0          |
     * | int        | 0                      | 0.0            |
     * | bool       | true                   | 1.0            |
     * | bool       | false                  | 0.0            |
     * | null       | null                   | 0.0            |
     * | string     | "3.14"                 | 3.14           |
     * | string     | "  3.14  "             | 3.14 (trimmed) |
     * | string     | "42"                   | 42.0           |
     * | string     | "1e3"                  | 1000.0         |
     * | string     | "-2.5"                 | -2.5           |
     * | string     | ""                     | CastException  |
     * | string     | "hello"                | CastException  |
     * | string     | "42abc"                | CastException  |
     * | array      | [1, 2, 3]              | CastException  |
     * | object     | stdClass               | CastException  |
     * | resource   | fopen(...)             | CastException  |
     *
     * Special Float Values:
     * ---------------------
     * Unlike toInt() and toJson(), this method allows INF and NAN values
     * to pass through unchanged, as they are valid float values in PHP.
     *
     * @param mixed $value The value to convert
     *
     * @return float The cast float
     */
    public static function toFloat(mixed $value): float
    {
        if (is_float($value)) {
            return $value;
        }

        if (is_int($value)) {
            return (float) $value;
        }

        if (is_bool($value)) {
            return $value ? self::FLOAT_TRUE : self::FLOAT_FALSE;
        }

        if (null === $value) {
            return self::FLOAT_FALSE;
        }

        if (is_string($value)) {
            $trimmed = trim($value);
            if (self::EMPTY_STRING === $trimmed) {
                self::throwCastException(self::ERR_EMPTY_STRING_TO_FLOAT);
            }
            if (!is_numeric($trimmed)) {
                self::throwCastException(self::ERR_NON_NUMERIC_STRING_TO_FLOAT, $trimmed);
            }

            return (float) $trimmed;
        }

        self::throwCastException(self::ERR_CANNOT_CAST_TO_FLOAT, $value);
    }
}
