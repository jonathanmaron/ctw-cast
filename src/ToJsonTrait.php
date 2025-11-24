<?php
declare(strict_types=1);

namespace Ctw\Cast;

use Ctw\Cast\Exception\CastException;
use JsonException;
use JsonSerializable;

/**
 * Trait providing JSON conversion functionality.
 */
trait ToJsonTrait
{
    /**
     * Converts a value to JSON string.
     *
     * Handles null, scalar types (string, int, float, bool), arrays, and objects.
     * Objects are converted if they implement JsonSerializable, have a toArray() method,
     * or can be converted via get_object_vars().
     *
     * @param mixed $value The value to convert
     * @param int   $flags JSON encoding flags (default: JSON_THROW_ON_ERROR | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
     * @param int   $depth Maximum depth for JSON encoding (default: 512, minimum: 1)
     * @return string The JSON encoded string
     */
    public static function toJson(
        mixed $value,
        int $flags = JSON_THROW_ON_ERROR | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE,
        int $depth = 512
    ): string {

        // @phpstan-ignore-next-line
        if (1 > $depth) {
            throw new CastException(sprintf('Depth must be at least 1, got %d.', $depth));
        }

        if (null === $value) {
            return 'null';
        }

        if (is_string($value) || is_int($value) || is_bool($value) || is_array($value)) {
            try {
                $result = json_encode($value, $flags, $depth);
                if (!is_string($result)) {
                    throw new CastException(
                        sprintf(
                            'Failed to encode %s to JSON: json_encode returned non-string value.',
                            get_debug_type($value)
                        )
                    );
                }

                return $result;
            } catch (JsonException $e) {
                throw new CastException(
                    sprintf('Failed to encode %s to JSON: %s', get_debug_type($value), $e->getMessage())
                );
            }
        }

        if (is_float($value)) {
            if (is_infinite($value)) {
                throw new CastException(
                    'Float value INF cannot be converted to JSON (JSON does not support infinite values).'
                );
            }
            if (is_nan($value)) {
                throw new CastException(
                    'Float value NAN cannot be converted to JSON (JSON does not support NaN values).'
                );
            }

            try {
                $result = json_encode($value, $flags, $depth);
                if (!is_string($result)) {
                    throw new CastException('Failed to encode float to JSON: json_encode returned non-string value.');
                }

                return $result;
            } catch (JsonException $e) {
                throw new CastException(sprintf('Failed to encode float to JSON: %s', $e->getMessage()));
            }
        }

        if (is_object($value)) {
            if ($value instanceof JsonSerializable) {
                try {
                    $result = json_encode($value, $flags, $depth);
                    if (!is_string($result)) {
                        throw new CastException(
                            sprintf(
                                'Failed to encode JsonSerializable object of type %s to JSON: json_encode returned non-string value.',
                                get_debug_type($value)
                            )
                        );
                    }

                    return $result;
                } catch (JsonException $e) {
                    throw new CastException(
                        sprintf('Failed to encode JsonSerializable object of type %s to JSON: %s',
                            get_debug_type($value), $e->getMessage())
                    );
                }
            }

            if (method_exists($value, 'toArray')) {
                $arrayValue = $value->toArray();
                if (!is_array($arrayValue)) {
                    throw new CastException(
                        sprintf('Object of type %s has toArray() method but it did not return an array.',
                            get_debug_type($value))
                    );
                }

                try {
                    $result = json_encode($arrayValue, $flags, $depth);
                    if (!is_string($result)) {
                        throw new CastException(
                            sprintf(
                                'Failed to encode object of type %s to JSON via toArray(): json_encode returned non-string value.',
                                get_debug_type($value)
                            )
                        );
                    }

                    return $result;
                } catch (JsonException $e) {
                    throw new CastException(
                        sprintf('Failed to encode object of type %s to JSON via toArray(): %s', get_debug_type($value),
                            $e->getMessage())
                    );
                }
            }

            try {
                $objectVars = get_object_vars($value);
                $result     = json_encode($objectVars, $flags, $depth);
                if (!is_string($result)) {
                    throw new CastException(
                        sprintf(
                            'Failed to encode object of type %s to JSON: json_encode returned non-string value.',
                            get_debug_type($value)
                        )
                    );
                }

                return $result;
            } catch (JsonException $e) {
                throw new CastException(
                    sprintf('Failed to encode object of type %s to JSON: %s', get_debug_type($value), $e->getMessage())
                );
            }
        }

        throw new CastException(sprintf('Value of type %s cannot be converted to JSON.', get_debug_type($value)));
    }
}
