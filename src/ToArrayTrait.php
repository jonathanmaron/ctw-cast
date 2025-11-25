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
     * Converts the input value to an array with intelligent handling of different
     * types. Supports JSON string parsing, object conversion via multiple strategies,
     * and wrapping of scalar values.
     *
     * Conversion Rules:
     * -----------------
     * | Input Type         | Input Example              | Output                    |
     * |--------------------|----------------------------|---------------------------|
     * | array              | [1, 2, 3]                  | [1, 2, 3]                 |
     * | null               | null                       | []                        |
     * | string             | ""                         | []                        |
     * | string             | "  "                       | []                        |
     * | string             | '{"a":1}'                  | ["a" => 1]                |
     * | string             | '[1,2,3]'                  | [1, 2, 3]                 |
     * | string             | '{invalid}'                | ['{invalid}']             |
     * | string             | "hello"                    | ["hello"]                 |
     * | int                | 42                         | [42]                      |
     * | float              | 3.14                       | [3.14]                    |
     * | bool               | true                       | [true]                    |
     * | object             | ArrayIterator([1,2])       | [1, 2] (via iterator)     |
     * | object             | (with toArray())           | toArray() result          |
     * | object             | stdClass{a:1, b:2}         | ["a" => 1, "b" => 2]      |
     * | resource           | fopen(...)                 | CastException             |
     *
     * Object Conversion Priority:
     * ---------------------------
     * 1. Traversable: Uses iterator_to_array()
     * 2. Has toArray() method: Calls toArray() and returns result if it's an array
     * 3. Other objects: Uses get_object_vars() to extract public properties
     *
     * JSON String Handling:
     * ---------------------
     * Strings starting with '{' or '[' are attempted to be parsed as JSON.
     * - Valid JSON arrays/objects are decoded and returned
     * - Invalid JSON or non-array results fall back to wrapping in array
     *
     * @param mixed $value The value to convert
     *
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

        self::throwCastException(self::ERR_CANNOT_CAST_TO_ARRAY, $value);
    }
}
