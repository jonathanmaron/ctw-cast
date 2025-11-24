<?php
declare(strict_types=1);

namespace Ctw\Cast;

use Ctw\Cast\Exception\CastException;
use JsonException;
use Traversable;

/**
 * Type-safe casting utility class.
 *
 * Provides static methods for converting values between different types
 * with comprehensive error handling and edge case support.
 */
final class Cast
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

        throw new CastException(
            sprintf('Value of type %s cannot be converted to int.', get_debug_type($value))
        );
    }

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

        throw new CastException(
            sprintf('Value of type %s cannot be converted to float.', get_debug_type($value))
        );
    }

    /**
     * Converts a value to boolean.
     *
     * @param mixed $value The value to convert
     * @return bool The converted boolean
     */
    public static function toBool(mixed $value): bool
    {
        if (is_bool($value)) {
            return $value;
        }

        if (is_int($value)) {
            return match ($value) {
                1       => true,
                0       => false,
                default => throw new CastException(
                    sprintf('Integer value %d cannot be converted to bool (only 0 and 1 are accepted).', $value)
                ),
            };
        }

        if (is_float($value)) {
            return match ($value) {
                1.0     => true,
                0.0     => false,
                default => throw new CastException(
                    sprintf('Float value %s cannot be converted to bool (only 0.0 and 1.0 are accepted).', $value)
                ),
            };
        }

        if (is_string($value)) {
            $lower = strtolower(trim($value));

            if (in_array($lower, ['true', '1', 'yes', 'on', 'y', 't'], true)) {
                return true;
            }

            if ('' === $lower || in_array($lower, ['false', '0', 'no', 'off', 'n', 'f'], true)) {
                return false;
            }

            throw new CastException(sprintf('String value "%s" cannot be converted to bool.', $value));
        }

        if (null === $value) {
            return false;
        }

        throw new CastException(
            sprintf('Value of type %s cannot be converted to bool.', get_debug_type($value))
        );
    }

    /**
     * Converts a value to array.
     *
     * @param mixed $value The value to convert
     * @return array<array-key, mixed> The converted array
     */
    public static function toArray(mixed $value): array
    {
        if (is_array($value)) {
            return $value;
        }

        if (null === $value) {
            return [];
        }

        if (is_string($value)) {
            $trimmed = trim($value);

            if ('' === $trimmed) {
                return [];
            }

            if (in_array($trimmed[0], ['{', '['], true)) {
                try {
                    $decoded = json_decode($trimmed, true, 512, JSON_THROW_ON_ERROR);
                    if (is_array($decoded)) {
                        return $decoded;
                    }
                } catch (JsonException) {
                }
            }

            return [$value];
        }

        if (is_object($value)) {
            if ($value instanceof Traversable) {
                return iterator_to_array($value);
            }

            if (method_exists($value, 'toArray')) {
                $result = $value->toArray();
                if (is_array($result)) {
                    return $result;
                }
            }

            return get_object_vars($value);
        }

        if (is_scalar($value)) {
            return [$value];
        }

        throw new CastException(
            sprintf(
                'Value of type %s cannot be converted to array.',
                get_debug_type($value)
            )
        );
    }
}
