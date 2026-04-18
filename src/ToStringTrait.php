<?php
declare(strict_types=1);

namespace Ctw\Cast;

use Stringable;

/**
 * Trait providing string conversion functionality.
 */
trait ToStringTrait
{
    /**
     * Casts a value to string.
     *
     * Converts the input value to its string representation following explicit,
     * predictable rules. Values that cannot be converted (arrays, resources,
     * objects without __toString) return an empty string.
     *
     * Conversion Rules:
     * -----------------
     * | Input Type | Input Example          | Output         |
     * |------------|------------------------|----------------|
     * | string     | "hello"                | "hello"        |
     * | int        | 42                     | "42"           |
     * | int        | -17                    | "-17"          |
     * | int        | 0                      | "0"            |
     * | float      | 3.14                   | "3.14"         |
     * | float      | -2.5                   | "-2.5"         |
     * | float      | 1.0                    | "1"            |
     * | float      | INF                    | "INF"          |
     * | float      | NAN                    | "NAN"          |
     * | bool       | true                   | "1"            |
     * | bool       | false                  | "0"            |
     * | null       | null                   | ""             |
     * | object     | (with __toString)      | __toString()   |
     * | object     | stdClass               | ""             |
     * | array      | [1, 2, 3]              | ""             |
     * | resource   | fopen(...)             | ""             |
     *
     * @param mixed $value The value to convert
     *
     * @return string The cast string, or "" if the value cannot be cast
     */
    public static function toString(mixed $value): string
    {
        if (is_string($value)) {
            return $value;
        }

        if (is_int($value) || is_float($value)) {
            return (string) $value;
        }

        if (is_bool($value)) {
            return $value ? self::STRING_TRUE : self::STRING_FALSE;
        }

        if (null === $value) {
            return self::EMPTY_STRING;
        }

        if (is_object($value) && method_exists($value, '__toString')) {
            assert($value instanceof Stringable);

            return $value->__toString();
        }

        return self::EMPTY_STRING;
    }
}
