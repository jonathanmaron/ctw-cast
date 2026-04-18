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
    /**
     * Casts a value to JSON string.
     *
     * Converts the input value to a valid JSON string representation. Handles all
     * scalar types, arrays, and objects with multiple conversion strategies.
     * When a value cannot be encoded (INF/NAN, invalid UTF-8, depth exceeded,
     * resources, or toArray() returning a non-array) the method returns "{}".
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
     * | float              | INF                        | "{}"                      |
     * | float              | NAN                        | "{}"                      |
     * | resource           | fopen(...)                 | "{}"                      |
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
     * - JSON_THROW_ON_ERROR: Always enforced internally to detect encoding errors
     *   (cannot be disabled; failures are caught and converted to "{}")
     *
     * @param mixed $value The value to convert
     * @param int   $flags JSON encoding flags (default: JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
     * @param int   $depth Maximum depth for JSON encoding (default: 512, minimum: 1)
     *
     * @return string The JSON encoded string, or "{}" if the value cannot be cast
     */
    public static function toJson(
        mixed $value,
        int $flags = JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE,
        int $depth = 512
    ): string {

        if (1 > $depth) {
            return self::EMPTY_JSON;
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
            } catch (JsonException) {
                return self::EMPTY_JSON;
            }
        }

        if (is_float($value)) {
            if (is_infinite($value) || is_nan($value)) {
                return self::EMPTY_JSON;
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
                } catch (JsonException) {
                    return self::EMPTY_JSON;
                }
            }

            if (method_exists($value, 'toArray')) {
                $arrayValue = $value->toArray();
                if (!is_array($arrayValue)) {
                    return self::EMPTY_JSON;
                }

                try {
                    $result = json_encode($arrayValue, $flags, $depth);
                    assert(is_string($result));

                    return $result;
                } catch (JsonException) {
                    return self::EMPTY_JSON;
                }
            }

            try {
                $result = json_encode(get_object_vars($value), $flags, $depth);
                assert(is_string($result));

                return $result;
            } catch (JsonException) {
                return self::EMPTY_JSON;
            }
        }

        return self::EMPTY_JSON;
    }
}
