<?php
declare(strict_types=1);

namespace Ctw\Cast;

/**
 * Trait providing float conversion functionality.
 */
trait ToFloatTrait
{
    private const string ERR_EMPTY_STRING_TO_FLOAT     = 'Empty string cannot be cast to float.';

    private const string ERR_NON_NUMERIC_STRING_TO_FLOAT = 'String value "%s" is not numeric and cannot be cast to float.';

    private const string ERR_CANNOT_CAST_TO_FLOAT      = 'Value of type %s cannot be cast to float.';

    /**
     * Casts a value to float.
     *
     * @param mixed $value The value to convert
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

        self::throwCastException(self::ERR_CANNOT_CAST_TO_FLOAT, get_debug_type($value));
    }
}
