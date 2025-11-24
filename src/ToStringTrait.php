<?php
declare(strict_types=1);

namespace Ctw\Cast;

use Ctw\Cast\Exception\CastException;

/**
 * Trait providing string conversion functionality.
 */
trait ToStringTrait
{
    /**
     * Converts a value to string.
     *
     * @param mixed $value The value to convert
     * @return string The converted string
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
            return $value ? '1' : '0';
        }

        if (null === $value) {
            return '';
        }

        if (is_object($value) && method_exists($value, '__toString')) {
            return (string) $value;
        }

        throw new CastException(
            sprintf('Value of type %s cannot be converted to string.', get_debug_type($value))
        );
    }
}
