<?php
declare(strict_types=1);

namespace Ctw\Cast;

/**
 * Trait providing string conversion functionality.
 */
trait ToStringTrait
{
    private const string ERR_CANNOT_CAST_TO_STRING = 'Value of type %s cannot be cast to string.';

    /**
     * Casts a value to string.
     *
     * @param mixed $value The value to convert
     * @return string The cast string
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
            /** @var object&\Stringable $value */
            return $value->__toString();
        }

        self::throwCastException(self::ERR_CANNOT_CAST_TO_STRING, get_debug_type($value));
    }
}
