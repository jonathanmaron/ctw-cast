<?php
declare(strict_types=1);

namespace Ctw\Cast;

/**
 * Trait providing boolean conversion functionality.
 */
trait ToBoolTrait
{
    private const array TRUTHY_STRINGS = ['true', '1', 'yes', 'on', 'y', 't'];

    /**
     * Casts a value to boolean.
     *
     * Converts the input value to a boolean. Unlike PHP's native (bool) cast which
     * accepts any value, this method only treats specific values as true or false
     * and returns false for any value that cannot be interpreted unambiguously.
     *
     * Conversion Rules:
     * -----------------
     * | Input Type | Input Example          | Output         |
     * |------------|------------------------|----------------|
     * | bool       | true                   | true           |
     * | bool       | false                  | false          |
     * | int        | 1                      | true           |
     * | int        | 0                      | false          |
     * | float      | 1.0                    | true           |
     * | float      | 0.0                    | false          |
     * | string     | "true"                 | true           |
     * | string     | "1"                    | true           |
     * | string     | "yes"                  | true           |
     * | string     | "on"                   | true           |
     * | string     | "y"                    | true           |
     * | string     | "t"                    | true           |
     * | string     | "false"                | false          |
     * | string     | "0"                    | false          |
     * | string     | "no"                   | false          |
     * | string     | "off"                  | false          |
     * | string     | "n"                    | false          |
     * | string     | "f"                    | false          |
     * | string     | ""                     | false          |
     * | string     | "  TRUE  "             | true (trimmed) |
     * | null       | null                   | false          |
     * | int        | 42                     | false          |
     * | int        | -1                     | false          |
     * | float      | 3.14                   | false          |
     * | float      | -1.0                   | false          |
     * | string     | "hello"                | false          |
     * | string     | "2"                    | false          |
     * | array      | [1, 2, 3]              | false          |
     * | object     | stdClass               | false          |
     * | resource   | fopen(...)             | false          |
     *
     * String Handling:
     * ----------------
     * String values are trimmed and compared case-insensitively.
     * - Truthy strings: "true", "1", "yes", "on", "y", "t"
     * - Falsy strings: "false", "0", "no", "off", "n", "f", ""
     *
     * @param mixed $value The value to convert
     *
     * @return bool The cast boolean, or false if the value cannot be cast
     */
    public static function toBool(mixed $value): bool
    {
        if (is_bool($value)) {
            return $value;
        }

        if (is_int($value)) {
            return match ($value) {
                self::INT_TRUE  => self::BOOL_TRUE,
                default         => self::EMPTY_BOOL,
            };
        }

        if (is_float($value)) {
            return match ($value) {
                self::FLOAT_TRUE  => self::BOOL_TRUE,
                default           => self::EMPTY_BOOL,
            };
        }

        if (is_string($value)) {
            $lower = strtolower(trim($value));

            return in_array($lower, self::TRUTHY_STRINGS, true) ? self::BOOL_TRUE : self::EMPTY_BOOL;
        }

        return self::EMPTY_BOOL;
    }
}
