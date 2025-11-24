<?php
declare(strict_types=1);

namespace Ctw\Cast;

use Ctw\Cast\Exception\CastException;

/**
 * Trait providing integer conversion functionality.
 */
trait ToIntTrait
{
    /**
     * Converts a value to integer.
     *
     * @param mixed $value The value to convert
     * @return int The converted integer
     */
    public static function toInt(mixed $value): int
    {
        if (is_int($value)) {
            return $value;
        }

        if (is_bool($value)) {
            return $value ? 1 : 0;
        }

        if (is_string($value)) {
            $trimmed = trim($value);
            if ('' === $trimmed) {
                throw new CastException('Empty string cannot be converted to int.');
            }
            if (!is_numeric($trimmed)) {
                throw new CastException(
                    sprintf('String value "%s" is not numeric and cannot be converted to int.', $trimmed)
                );
            }
            $numericValue = $trimmed + 0;
            if (is_float($numericValue)) {
                if (PHP_INT_MAX < $numericValue || PHP_INT_MIN > $numericValue) {
                    throw new CastException(
                        sprintf('Numeric string value "%s" is out of integer range.', $trimmed)
                    );
                }

                return (int) round($numericValue);
            }

            return $numericValue;
        }

        if (is_float($value)) {
            if (is_infinite($value) || is_nan($value)) {
                throw new CastException(
                    sprintf('Float value %s (infinite or NaN) cannot be converted to int.', $value)
                );
            }
            if (PHP_INT_MAX < $value || PHP_INT_MIN > $value) {
                throw new CastException(
                    sprintf(
                        'Float value %s is out of integer range (min: %d, max: %d).',
                        $value,
                        PHP_INT_MIN,
                        PHP_INT_MAX
                    )
                );
            }

            return (int) round($value);
        }

        if (null === $value) {
            return 0;
        }

        throw new CastException(sprintf('Value of type %s cannot be converted to int.', get_debug_type($value)));
    }
}
