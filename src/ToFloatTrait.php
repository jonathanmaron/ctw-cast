<?php
declare(strict_types=1);

namespace Ctw\Cast;

use Ctw\Cast\Exception\CastException;

/**
 * Trait providing float conversion functionality.
 */
trait ToFloatTrait
{
    /**
     * Converts a value to float.
     *
     * @param mixed $value The value to convert
     * @return float The converted float
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
            return $value ? 1.0 : 0.0;
        }

        if (null === $value) {
            return 0.0;
        }

        if (is_string($value)) {
            $trimmed = trim($value);
            if ('' === $trimmed) {
                throw new CastException('Empty string cannot be converted to float.');
            }
            if (!is_numeric($trimmed)) {
                throw new CastException(
                    sprintf('String value "%s" is not numeric and cannot be converted to float.', $trimmed)
                );
            }

            return (float) $trimmed;
        }

        throw new CastException(sprintf('Value of type %s cannot be converted to float.', get_debug_type($value)));
    }
}
