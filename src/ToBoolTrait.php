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
     * @param mixed $value The value to convert
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

        self::throwCastException(self::ERR_CANNOT_CAST_TO_BOOL, get_debug_type($value));
    }
}
