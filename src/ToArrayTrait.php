<?php
declare(strict_types=1);

namespace Ctw\Cast;

use Ctw\Cast\Exception\CastException;
use JsonException;
use Traversable;

/**
 * Trait providing array conversion functionality.
 */
trait ToArrayTrait
{
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

        throw new CastException(sprintf('Value of type %s cannot be converted to array.', get_debug_type($value)));
    }
}
