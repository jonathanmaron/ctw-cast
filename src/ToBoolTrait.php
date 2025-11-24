<?php
declare(strict_types=1);

namespace Ctw\Cast;

use Ctw\Cast\Exception\CastException;

/**
 * Trait providing boolean conversion functionality.
 */
trait ToBoolTrait
{
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

        throw new CastException(sprintf('Value of type %s cannot be converted to bool.', get_debug_type($value)));
    }
}
