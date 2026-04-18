<?php
declare(strict_types=1);

namespace Ctw\Cast;

/**
 * Trait providing float conversion functionality.
 */
trait ToFloatTrait
{
    /**
     * Casts a value to float.
     *
     * Converts the input value to a float. Values that cannot be interpreted
     * as a float return 0.0.
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
     * | string     | ""                     | 0.0            |
     * | string     | "hello"                | 0.0            |
     * | string     | "42abc"                | 0.0            |
     * | array      | [1, 2, 3]              | 0.0            |
     * | object     | stdClass               | 0.0            |
     * | resource   | fopen(...)             | 0.0            |
     *
     * Special Float Values:
     * ---------------------
     * Unlike toInt() and toJson(), this method allows INF and NAN values
     * to pass through unchanged, as they are valid float values in PHP.
     *
     * @param mixed $value The value to convert
     *
     * @return float The cast float, or 0.0 if the value cannot be cast
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
            return $value ? self::FLOAT_TRUE : self::EMPTY_FLOAT;
        }

        if (null === $value) {
            return self::EMPTY_FLOAT;
        }

        if (is_string($value)) {
            $trimmed = trim($value);
            if (self::EMPTY_STRING === $trimmed) {
                return self::EMPTY_FLOAT;
            }
            if (!is_numeric($trimmed)) {
                return self::EMPTY_FLOAT;
            }

            return (float) $trimmed;
        }

        return self::EMPTY_FLOAT;
    }
}
