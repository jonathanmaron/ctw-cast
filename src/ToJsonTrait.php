<?php
declare(strict_types=1);

namespace Ctw\Cast;

use JsonException;
use JsonSerializable;

/**
 * Trait providing JSON conversion functionality.
 */
trait ToJsonTrait
{
    private const string ERR_DEPTH_INVALID                    = 'Depth must be at least 1, got %d.';

    private const string ERR_ENCODE_NON_STRING                = 'Failed to encode %s to JSON: json_encode returned non-string value.';

    private const string ERR_ENCODE_FAILED                    = 'Failed to encode %s to JSON: %s';

    private const string ERR_FLOAT_INFINITE                   = 'Float value INF cannot be cast to JSON (JSON does not support infinite values).';

    private const string ERR_FLOAT_NAN                        = 'Float value NAN cannot be cast to JSON (JSON does not support NaN values).';

    private const string ERR_FLOAT_ENCODE_NON_STRING          = 'Failed to encode float to JSON: json_encode returned non-string value.';

    private const string ERR_FLOAT_ENCODE_FAILED              = 'Failed to encode float to JSON: %s';

    private const string ERR_JSON_SERIALIZABLE_NON_STRING     = 'Failed to encode JsonSerializable object of type %s to JSON: json_encode returned non-string value.';

    private const string ERR_JSON_SERIALIZABLE_FAILED         = 'Failed to encode JsonSerializable object of type %s to JSON: %s';

    private const string ERR_TO_ARRAY_NOT_ARRAY               = 'Object of type %s has toArray() method but it did not return an array.';

    private const string ERR_TO_ARRAY_ENCODE_NON_STRING       = 'Failed to encode object of type %s to JSON via toArray(): json_encode returned non-string value.';

    private const string ERR_TO_ARRAY_ENCODE_FAILED           = 'Failed to encode object of type %s to JSON via toArray(): %s';

    private const string ERR_OBJECT_ENCODE_NON_STRING         = 'Failed to encode object of type %s to JSON: json_encode returned non-string value.';

    private const string ERR_OBJECT_ENCODE_FAILED             = 'Failed to encode object of type %s to JSON: %s';

    private const string ERR_CANNOT_CAST_TO_JSON              = 'Value of type %s cannot be cast to JSON.';

    /**
     * Casts a value to JSON string.
     *
     * Handles null, scalar types (string, int, float, bool), arrays, and objects.
     * Objects are cast if they implement JsonSerializable, have a toArray() method,
     * or can be cast via get_object_vars().
     *
     * @param mixed $value The value to convert
     * @param int   $flags JSON encoding flags (default: JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
     * @param int   $depth Maximum depth for JSON encoding (default: 512, minimum: 1)
     * @return string The JSON encoded string
     */
    public static function toJson(
        mixed $value,
        int $flags = JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE,
        int $depth = 512
    ): string {

        $flags |= JSON_THROW_ON_ERROR;

        // @phpstan-ignore-next-line
        if (1 > $depth) {
            self::throwCastException(self::ERR_DEPTH_INVALID, $depth);
        }

        if (null === $value) {
            return 'null';
        }

        if (is_string($value) || is_int($value) || is_bool($value) || is_array($value)) {
            try {
                $result = json_encode($value, $flags, $depth);
                if (!is_string($result)) {
                    self::throwCastException(self::ERR_ENCODE_NON_STRING, get_debug_type($value));
                }

                return $result;
            } catch (JsonException $e) {
                self::throwCastException(self::ERR_ENCODE_FAILED, get_debug_type($value), $e->getMessage());
            }
        }

        if (is_float($value)) {
            if (is_infinite($value)) {
                self::throwCastException(self::ERR_FLOAT_INFINITE);
            }
            if (is_nan($value)) {
                self::throwCastException(self::ERR_FLOAT_NAN);
            }

            try {
                $result = json_encode($value, $flags, $depth);
                if (!is_string($result)) {
                    self::throwCastException(self::ERR_FLOAT_ENCODE_NON_STRING);
                }

                return $result;
            } catch (JsonException $e) {
                self::throwCastException(self::ERR_FLOAT_ENCODE_FAILED, $e->getMessage());
            }
        }

        if (is_object($value)) {
            if ($value instanceof JsonSerializable) {
                try {
                    $result = json_encode($value, $flags, $depth);
                    if (!is_string($result)) {
                        self::throwCastException(self::ERR_JSON_SERIALIZABLE_NON_STRING, get_debug_type($value));
                    }

                    return $result;
                } catch (JsonException $e) {
                    self::throwCastException(self::ERR_JSON_SERIALIZABLE_FAILED, get_debug_type($value), $e->getMessage());
                }
            }

            if (method_exists($value, 'toArray')) {
                $arrayValue = $value->toArray();
                if (!is_array($arrayValue)) {
                    self::throwCastException(self::ERR_TO_ARRAY_NOT_ARRAY, get_debug_type($value));
                }

                try {
                    $result = json_encode($arrayValue, $flags, $depth);
                    if (!is_string($result)) {
                        self::throwCastException(self::ERR_TO_ARRAY_ENCODE_NON_STRING, get_debug_type($value));
                    }

                    return $result;
                } catch (JsonException $e) {
                    self::throwCastException(self::ERR_TO_ARRAY_ENCODE_FAILED, get_debug_type($value), $e->getMessage());
                }
            }

            try {
                $objectVars = get_object_vars($value);
                $result     = json_encode($objectVars, $flags, $depth);
                if (!is_string($result)) {
                    self::throwCastException(self::ERR_OBJECT_ENCODE_NON_STRING, get_debug_type($value));
                }

                return $result;
            } catch (JsonException $e) {
                self::throwCastException(self::ERR_OBJECT_ENCODE_FAILED, get_debug_type($value), $e->getMessage());
            }
        }

        self::throwCastException(self::ERR_CANNOT_CAST_TO_JSON, get_debug_type($value));
    }
}
