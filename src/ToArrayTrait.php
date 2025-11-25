<?php
declare(strict_types=1);

namespace Ctw\Cast;

use JsonException;
use Traversable;

/**
 * Trait providing array conversion functionality.
 */
trait ToArrayTrait
{
    private const string ERR_CANNOT_CAST_TO_ARRAY = 'Value of type %s cannot be cast to array.';

    private const array  JSON_START_CHARS         = ['{', '['];

    /**
     * Casts a value to array.
     *
     * @param mixed $value The value to convert
     * @return array<array-key, mixed> The cast array
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

            if (self::EMPTY_STRING === $trimmed) {
                return [];
            }

            if (in_array($trimmed[0], self::JSON_START_CHARS, true)) {
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

        self::throwCastException(self::ERR_CANNOT_CAST_TO_ARRAY, get_debug_type($value));
    }
}
