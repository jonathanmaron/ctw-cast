<?php
declare(strict_types=1);

namespace Ctw\Cast;

/**
 * Trait providing boolean conversion functionality.
 */
trait ToBoolTrait
{
    private const string ERR_INT_CANNOT_CAST_TO_BOOL    = 'Integer value %d cannot be cast to bool (only 0 and 1 are accepted).';

    private const string ERR_FLOAT_CANNOT_CAST_TO_BOOL  = 'Float value %s cannot be cast to bool (only 0.0 and 1.0 are accepted).';

    private const string ERR_STRING_CANNOT_CAST_TO_BOOL = 'String value "%s" cannot be cast to bool.';

    private const string ERR_CANNOT_CAST_TO_BOOL        = 'Value of type %s cannot be cast to bool.';

    private const array  TRUTHY_STRINGS                 = ['true', '1', 'yes', 'on', 'y', 't'];

    private const array  FALSY_STRINGS                  = ['false', '0', 'no', 'off', 'n', 'f'];

    /**
     * Casts a value to boolean.
     *
     * Converts the input value to a boolean with strict validation. Unlike PHP's
     * native (bool) cast which accepts any value, this method only accepts specific
     * values that have clear boolean semantics and throws exceptions for ambiguous cases.
     *
     * Conversion Rules:
     * -----------------
     * | Input Type | Input Example          | Output         |
     * |------------|------------------------|----------------|
     * | bool       | true                   | true           |
     * | bool       | false                  | false          |
     * | int        | 1                      | true           |
     * | int        | 0                      | false          |
     * | int        | 42                     | CastException  |
     * | int        | -1                     | CastException  |
     * | float      | 1.0                    | true           |
     * | float      | 0.0                    | false          |
     * | float      | 3.14                   | CastException  |
     * | float      | -1.0                   | CastException  |
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
     * | string     | "hello"                | CastException  |
     * | string     | "2"                    | CastException  |
     * | null       | null                   | false          |
     * | array      | [1, 2, 3]              | CastException  |
     * | object     | stdClass               | CastException  |
     * | resource   | fopen(...)             | CastException  |
     *
     * String Handling:
     * ----------------
     * String values are trimmed and compared case-insensitively.
     * - Truthy strings: "true", "1", "yes", "on", "y", "t"
     * - Falsy strings: "false", "0", "no", "off", "n", "f", ""
     *
     * @param mixed $value The value to convert
     *
     * @return bool The cast boolean
     */
    public static function toBool(mixed $value): bool
    {
        if (is_bool($value)) {
            return $value;
        }

        if (is_int($value)) {
            return match ($value) {
                self::INT_TRUE  => true,
                self::INT_FALSE => false,
                default         => self::throwCastException(self::ERR_INT_CANNOT_CAST_TO_BOOL, $value),
            };
        }

        if (is_float($value)) {
            return match ($value) {
                self::FLOAT_TRUE  => true,
                self::FLOAT_FALSE => false,
                default           => self::throwCastException(self::ERR_FLOAT_CANNOT_CAST_TO_BOOL, $value),
            };
        }

        if (is_string($value)) {

            $lower = strtolower(trim($value));

            if (in_array($lower, self::TRUTHY_STRINGS, true)) {
                return true;
            }

            if (self::EMPTY_STRING === $lower || in_array($lower, self::FALSY_STRINGS, true)) {
                return false;
            }

            self::throwCastException(self::ERR_STRING_CANNOT_CAST_TO_BOOL, $value);
        }

        if (null === $value) {
            return false;
        }

        self::throwCastException(self::ERR_CANNOT_CAST_TO_BOOL, $value);
    }
}
