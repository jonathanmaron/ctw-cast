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
    private const string ERR_DEPTH_INVALID            = 'Depth must be at least 1, got %d.';

    private const string ERR_ENCODE_FAILED            = 'Failed to encode %s to JSON: %s';

    private const string ERR_FLOAT_INFINITE           = 'Float value INF cannot be cast to JSON (JSON does not support infinite values).';

    private const string ERR_FLOAT_NAN                = 'Float value NAN cannot be cast to JSON (JSON does not support NaN values).';

    private const string ERR_JSON_SERIALIZABLE_FAILED = 'Failed to encode JsonSerializable object of type %s to JSON: %s';

    private const string ERR_TO_ARRAY_NOT_ARRAY       = 'Object of type %s has toArray() method but it did not return an array.';

    private const string ERR_TO_ARRAY_ENCODE_FAILED   = 'Failed to encode object of type %s to JSON via toArray(): %s';

    private const string ERR_OBJECT_ENCODE_FAILED     = 'Failed to encode object of type %s to JSON: %s';

    private const string ERR_CANNOT_CAST_TO_JSON      = 'Value of type %s cannot be cast to JSON.';

    /**
     * Casts a value to JSON string.
     *
     * Converts the input value to a valid JSON string representation. Handles all
     * scalar types, arrays, and objects with multiple conversion strategies.
     * JSON_THROW_ON_ERROR is always enforced internally for consistent exception handling.
     *
     * Conversion Rules:
     * -----------------
     * | Input Type         | Input Example              | Output                    |
     * |--------------------|----------------------------|---------------------------|
     * | null               | null                       | "null"                    |
     * | string             | "hello"                    | "\"hello\""               |
     * | string             | "with/slash"               | "\"with/slash\""          |
     * | int                | 42                         | "42"                      |
     * | int                | -17                        | "-17"                     |
     * | bool               | true                       | "true"                    |
     * | bool               | false                      | "false"                   |
     * | float              | 3.14                       | "3.14"                    |
     * | array              | [1, 2, 3]                  | "[1,2,3]"                 |
     * | array              | ["a" => 1]                 | "{\"a\":1}"               |
     * | object             | JsonSerializable           | via jsonSerialize()       |
     * | object             | (with toArray())           | via toArray()             |
     * | object             | stdClass{a:1}              | "{\"a\":1}"               |
     * | float              | INF                        | CastException             |
     * | float              | NAN                        | CastException             |
     * | resource           | fopen(...)                 | CastException             |
     *
     * Object Conversion Priority:
     * ---------------------------
     * 1. JsonSerializable: Uses json_encode() which calls jsonSerialize()
     * 2. Has toArray() method: Calls toArray() and encodes the resulting array
     * 3. Other objects: Uses get_object_vars() to extract public properties
     *
     * Default Flags:
     * --------------
     * - JSON_UNESCAPED_SLASHES: Forward slashes are not escaped
     * - JSON_UNESCAPED_UNICODE: Unicode characters are not escaped to \uXXXX
     * - JSON_THROW_ON_ERROR: Always enforced internally to catch encoding errors (cannot be disabled)
     *
     * @param mixed $value The value to convert
     * @param int   $flags JSON encoding flags (default: JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
     * @param int   $depth Maximum depth for JSON encoding (default: 512, minimum: 1)
     *
     * @return string The JSON encoded string
     */
    public static function toJson(
        mixed $value,
        int $flags = JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE,
        int $depth = 512
    ): string {

        if (1 > $depth) {
            self::throwCastException(self::ERR_DEPTH_INVALID, $depth);
        }

        if (null === $value) {
            return 'null';
        }

        $flags |= JSON_THROW_ON_ERROR;

        if (is_string($value) || is_int($value) || is_bool($value) || is_array($value)) {
            try {
                $result = json_encode($value, $flags, $depth);
                assert(is_string($result));

                return $result;
            } catch (JsonException $jsonException) {
                self::throwCastException(self::ERR_ENCODE_FAILED, get_debug_type($value), $jsonException->getMessage());
            }
        }

        if (is_float($value)) {
            if (is_infinite($value)) {
                self::throwCastException(self::ERR_FLOAT_INFINITE);
            }
            if (is_nan($value)) {
                self::throwCastException(self::ERR_FLOAT_NAN);
            }

            $result = json_encode($value, $flags, $depth);
            assert(is_string($result));

            return $result;
        }

        if (is_object($value)) {
            if ($value instanceof JsonSerializable) {
                try {
                    $result = json_encode($value, $flags, $depth);
                    assert(is_string($result));

                    return $result;
                } catch (JsonException $jsonException) {
                    self::throwCastException(self::ERR_JSON_SERIALIZABLE_FAILED, $value, $jsonException->getMessage());
                }
            }

            if (method_exists($value, 'toArray')) {
                $arrayValue = $value->toArray();
                if (!is_array($arrayValue)) {
                    self::throwCastException(self::ERR_TO_ARRAY_NOT_ARRAY, $value);
                }

                try {
                    $result = json_encode($arrayValue, $flags, $depth);
                    assert(is_string($result));

                    return $result;
                } catch (JsonException $jsonException) {
                    self::throwCastException(self::ERR_TO_ARRAY_ENCODE_FAILED, $value, $jsonException->getMessage());
                }
            }

            try {
                $result = json_encode(get_object_vars($value), $flags, $depth);
                assert(is_string($result));

                return $result;
            } catch (JsonException $jsonException) {
                self::throwCastException(self::ERR_OBJECT_ENCODE_FAILED, $value, $jsonException->getMessage());
            }
        }

        self::throwCastException(self::ERR_CANNOT_CAST_TO_JSON, $value);
    }
}
