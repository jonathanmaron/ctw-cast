<?php
declare(strict_types=1);

namespace CtwTest\Cast;

use Ctw\Cast\Cast;
use PHPUnit\Framework\TestCase;
use stdClass;

final class ToJsonTest extends TestCase
{
    /**
     * Test that toJson returns the literal "null" when given null.
     */
    public function testToJsonConvertsNullToJsonNull(): void
    {
        $actual = Cast::toJson(null);

        self::assertSame('null', $actual);
    }

    /**
     * Test that toJson returns a quoted JSON string when given a plain string.
     */
    public function testToJsonConvertsStringToJsonString(): void
    {
        $input  = 'hello world';
        $actual = Cast::toJson($input);

        self::assertSame('"hello world"', $actual);
    }

    /**
     * Test that toJson returns a pair of quotes when given an empty string.
     */
    public function testToJsonConvertsEmptyStringToJsonEmptyString(): void
    {
        $input  = '';
        $actual = Cast::toJson($input);

        self::assertSame('""', $actual);
    }

    /**
     * Test that toJson escapes control and quote characters when given a string containing them.
     */
    public function testToJsonEscapesSpecialCharactersInString(): void
    {
        $input  = "line1\nline2\ttab\"quote";
        $actual = Cast::toJson($input);

        self::assertSame('"line1\nline2\ttab\"quote"', $actual);
    }

    /**
     * Test that toJson leaves multibyte characters unescaped when given a Unicode string.
     */
    public function testToJsonPreservesUnicodeCharacters(): void
    {
        $input  = 'Hello 世界 🌍';
        $actual = Cast::toJson($input);

        self::assertSame('"Hello 世界 🌍"', $actual);
    }

    /**
     * Test that toJson returns the number literal when given a positive integer.
     */
    public function testToJsonConvertsPositiveIntegerToJsonNumber(): void
    {
        $input  = 42;
        $actual = Cast::toJson($input);

        self::assertSame('42', $actual);
    }

    /**
     * Test that toJson returns the number literal when given a negative integer.
     */
    public function testToJsonConvertsNegativeIntegerToJsonNumber(): void
    {
        $input  = -42;
        $actual = Cast::toJson($input);

        self::assertSame('-42', $actual);
    }

    /**
     * Test that toJson returns "0" when given the integer zero.
     */
    public function testToJsonConvertsZeroToJsonZero(): void
    {
        $input  = 0;
        $actual = Cast::toJson($input);

        self::assertSame('0', $actual);
    }

    /**
     * Test that toJson returns the number literal when given PHP_INT_MAX.
     */
    public function testToJsonConvertsPhpIntMaxToJson(): void
    {
        $input  = PHP_INT_MAX;
        $actual = Cast::toJson($input);

        self::assertSame((string) PHP_INT_MAX, $actual);
    }

    /**
     * Test that toJson returns the number literal when given PHP_INT_MIN.
     */
    public function testToJsonConvertsPhpIntMinToJson(): void
    {
        $input  = PHP_INT_MIN;
        $actual = Cast::toJson($input);

        self::assertSame((string) PHP_INT_MIN, $actual);
    }

    /**
     * Test that toJson returns the number literal when given a positive float.
     */
    public function testToJsonConvertsPositiveFloatToJsonNumber(): void
    {
        $input  = 3.14;
        $actual = Cast::toJson($input);

        self::assertSame('3.14', $actual);
    }

    /**
     * Test that toJson returns the number literal when given a negative float.
     */
    public function testToJsonConvertsNegativeFloatToJsonNumber(): void
    {
        $input  = -3.14;
        $actual = Cast::toJson($input);

        self::assertSame('-3.14', $actual);
    }

    /**
     * Test that toJson returns "0" when given the float zero.
     */
    public function testToJsonConvertsZeroFloatToJsonZero(): void
    {
        $input  = 0.0;
        $actual = Cast::toJson($input);

        self::assertSame('0', $actual);
    }

    /**
     * Test that toJson preserves precision when given a float with many decimal places.
     */
    public function testToJsonConvertsFloatWithManyDecimals(): void
    {
        $input  = 3.141592653589793;
        $actual = Cast::toJson($input);

        self::assertSame('3.141592653589793', $actual);
    }

    /**
     * Test that toJson returns "{}" when given positive infinity, which is not encodable.
     */
    public function testToJsonConvertsInfiniteFloatToEmptyObject(): void
    {
        $actual = Cast::toJson(INF);

        self::assertSame('{}', $actual);
    }

    /**
     * Test that toJson returns "{}" when given negative infinity, which is not encodable.
     */
    public function testToJsonConvertsNegativeInfiniteFloatToEmptyObject(): void
    {
        $actual = Cast::toJson(-INF);

        self::assertSame('{}', $actual);
    }

    /**
     * Test that toJson returns "{}" when given a NaN float, which is not encodable.
     */
    public function testToJsonConvertsNanFloatToEmptyObject(): void
    {
        $actual = Cast::toJson(NAN);

        self::assertSame('{}', $actual);
    }

    /**
     * Test that toJson returns "true" when given the boolean true.
     */
    public function testToJsonConvertsTrueBooleanToJsonTrue(): void
    {
        $input  = true;
        $actual = Cast::toJson($input);

        self::assertSame('true', $actual);
    }

    /**
     * Test that toJson returns "false" when given the boolean false.
     */
    public function testToJsonConvertsFalseBooleanToJsonFalse(): void
    {
        $input  = false;
        $actual = Cast::toJson($input);

        self::assertSame('false', $actual);
    }

    /**
     * Test that toJson returns "[]" when given an empty array.
     */
    public function testToJsonConvertsEmptyArrayToJsonEmptyArray(): void
    {
        $input  = [];
        $actual = Cast::toJson($input);

        self::assertSame('[]', $actual);
    }

    /**
     * Test that toJson returns a JSON array when given a sequential indexed array.
     */
    public function testToJsonConvertsIndexedArrayToJsonArray(): void
    {
        $input  = [1, 2, 3, 4, 5];
        $actual = Cast::toJson($input);

        self::assertSame('[1,2,3,4,5]', $actual);
    }

    /**
     * Test that toJson returns a JSON object when given an associative array.
     */
    public function testToJsonConvertsAssociativeArrayToJsonObject(): void
    {
        $input  = [
            'name' => 'John',
            'age' => 30,
            'active' => true,
        ];
        $actual = Cast::toJson($input);

        self::assertSame('{"name":"John","age":30,"active":true}', $actual);
    }

    /**
     * Test that toJson encodes the full structure when given a nested array.
     */
    public function testToJsonConvertsNestedArrayToJson(): void
    {
        $input = [
            'user'    => [
                'name'    => 'John',
                'age'     => 30,
                'hobbies' => ['reading', 'coding'],
            ],
            'active'  => true,
            'balance' => 1234.56,
        ];
        $actual = Cast::toJson($input);

        self::assertSame(
            '{"user":{"name":"John","age":30,"hobbies":["reading","coding"]},"active":true,"balance":1234.56}',
            $actual
        );
    }

    /**
     * Test that toJson preserves each value's type when given an array of mixed types.
     */
    public function testToJsonConvertsArrayWithMixedTypesToJson(): void
    {
        $input  = ['string', 42, 3.14, true, null, ['nested']];
        $actual = Cast::toJson($input);

        self::assertSame('["string",42,3.14,true,null,["nested"]]', $actual);
    }

    /**
     * Test that toJson encodes the public properties when given a populated stdClass object.
     */
    public function testToJsonConvertsStdClassObjectToJson(): void
    {
        $object       = new stdClass();
        $object->name = 'John';
        $object->age  = 30;

        $actual = Cast::toJson($object);

        self::assertSame('{"name":"John","age":30}', $actual);
    }

    /**
     * Test that toJson returns "[]" when given an empty stdClass object.
     */
    public function testToJsonConvertsEmptyStdClassObjectToJson(): void
    {
        $object = new stdClass();
        $actual = Cast::toJson($object);

        self::assertSame('[]', $actual);
    }

    /**
     * Test that toJson encodes the method result when given an object exposing a toArray method.
     */
    public function testToJsonConvertsObjectWithToArrayMethodToJson(): void
    {
        $object = new class() {
            /**
             * @return array<string, mixed>
             */
            public function toArray(): array
            {
                return [
                    'name' => 'Test',
                    'value' => 123,
                ];
            }
        };

        $actual = Cast::toJson($object);

        self::assertSame('{"name":"Test","value":123}', $actual);
    }

    /**
     * Test that toJson uses the serialized form when given a JsonSerializable object.
     */
    public function testToJsonConvertsJsonSerializableObjectToJson(): void
    {
        $object = new class() implements \JsonSerializable {
            /**
             * @return array<string, mixed>
             */
            public function jsonSerialize(): array
            {
                return [
                    'type' => 'custom',
                    'data' => [1, 2, 3],
                ];
            }
        };

        $actual = Cast::toJson($object);

        self::assertSame('{"type":"custom","data":[1,2,3]}', $actual);
    }

    /**
     * Test that toJson encodes the public properties when given a plain object without toArray or JsonSerializable.
     */
    public function testToJsonConvertsObjectWithPublicPropertiesToJson(): void
    {
        $object = new class() {
            public string $name = 'John';

            public int $age     = 30;
        };

        $actual = Cast::toJson($object);

        self::assertSame('{"name":"John","age":30}', $actual);
    }

    /**
     * Test that toJson returns "{}" when given an object whose toArray returns a non-array.
     */
    public function testToJsonConvertsObjectWithToArrayReturningNonArrayToEmptyObject(): void
    {
        $object = new class() {
            public function toArray(): string
            {
                return 'not an array';
            }
        };

        $actual = Cast::toJson($object);

        self::assertSame('{}', $actual);
    }

    /**
     * Test that toJson escapes forward slashes when given custom flags omitting JSON_UNESCAPED_SLASHES.
     */
    public function testToJsonAcceptsCustomFlags(): void
    {
        $input  = [
            'name' => 'John',
            'url' => 'https://example.com/path',
        ];
        $actual = Cast::toJson($input, JSON_THROW_ON_ERROR);

        // Without JSON_UNESCAPED_SLASHES, slashes will be escaped
        self::assertSame('{"name":"John","url":"https:\/\/example.com\/path"}', $actual);
    }

    /**
     * Test that toJson produces indented output when given the JSON_PRETTY_PRINT flag.
     */
    public function testToJsonWithPrettyPrintFlag(): void
    {
        $input    = [
            'name' => 'John',
            'age' => 30,
        ];
        $actual   = Cast::toJson($input, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT);
        $expected = <<<'JSON'
{
    "name": "John",
    "age": 30
}
JSON;

        self::assertSame($expected, $actual);
    }

    /**
     * Test that toJson encodes successfully when given a nested array within the default depth limit.
     */
    public function testToJsonAcceptsCustomDepth(): void
    {
        $input = [
            'level1' => [
                'level2' => [
                    'level3' => 'deep',
                ],
            ],
        ];

        // This should work with default depth
        $actual = Cast::toJson($input);
        self::assertSame('{"level1":{"level2":{"level3":"deep"}}}', $actual);
    }

    /**
     * Test that toJson returns "{}" when given an array nested deeper than the configured depth.
     */
    public function testToJsonConvertsExceedingMaxDepthToEmptyObject(): void
    {
        $input = [
            'level1' => [
                'level2' => [
                    'level3' => 'deep',
                ],
            ],
        ];

        $actual = Cast::toJson($input, JSON_THROW_ON_ERROR, 2);

        self::assertSame('{}', $actual);
    }

    /**
     * Test that toJson returns "{}" when given a depth of zero, below the minimum of one.
     */
    public function testToJsonConvertsDepthLessThanOneToEmptyObject(): void
    {
        $actual = Cast::toJson(['test'], JSON_THROW_ON_ERROR, 0);

        self::assertSame('{}', $actual);
    }

    /**
     * Test that toJson returns "{}" when given a negative depth.
     */
    public function testToJsonConvertsNegativeDepthToEmptyObject(): void
    {
        $actual = Cast::toJson(['test'], JSON_THROW_ON_ERROR, -1);

        self::assertSame('{}', $actual);
    }

    /**
     * Test that toJson returns "{}" when given an open resource.
     */
    public function testToJsonConvertsResourceTypeToEmptyObject(): void
    {
        $resource = fopen('php://memory', 'r');
        if (false === $resource) {
            self::fail('Failed to open resource');
        }

        try {
            $actual = Cast::toJson($resource);
        } finally {
            fclose($resource);
        }

        self::assertSame('{}', $actual);
    }

    /**
     * Test that toJson returns "{}" when given a closed resource.
     */
    public function testToJsonConvertsClosedResourceToEmptyObject(): void
    {
        $resource = fopen('php://memory', 'r');
        if (false === $resource) {
            self::fail('Failed to open resource');
        }
        fclose($resource);

        $actual = Cast::toJson($resource);

        self::assertSame('{}', $actual);
    }

    /**
     * Test that toJson returns "{}" when given a string containing invalid UTF-8.
     */
    public function testToJsonConvertsInvalidUtf8StringToEmptyObject(): void
    {
        $invalidUtf8 = "\x80\x81\x82\x83";

        $actual = Cast::toJson($invalidUtf8);

        self::assertSame('{}', $actual);
    }

    /**
     * Test that toJson returns "[]" when given an object exposing no public properties.
     */
    public function testToJsonConvertsObjectWithPrivateProperties(): void
    {
        $object = new class() {
        };

        $actual = Cast::toJson($object);

        self::assertSame('[]', $actual);
    }

    /**
     * Test that toJson prefers jsonSerialize when given an object implementing both JsonSerializable and toArray.
     */
    public function testToJsonPrefersJsonSerializableOverToArray(): void
    {
        $object = new class() implements \JsonSerializable {
            /**
             * @return array<string, mixed>
             */
            public function jsonSerialize(): array
            {
                return [
                    'from' => 'jsonSerialize',
                ];
            }

            /**
             * @return array<string, mixed>
             */
            public function toArray(): array
            {
                return [
                    'from' => 'toArray',
                ];
            }
        };

        $actual = Cast::toJson($object);

        self::assertSame('{"from":"jsonSerialize"}', $actual);
    }

    /**
     * Test that toJson falls back to public properties when given a plain object with neither toArray nor JsonSerializable.
     */
    public function testToJsonUsesGetObjectVarsForPlainObject(): void
    {
        $object         = new stdClass();
        $object->prop1  = 'value1';
        $object->prop2  = 42;
        $object->nested = [
            'a' => 'b',
        ];

        $actual = Cast::toJson($object);

        self::assertSame('{"prop1":"value1","prop2":42,"nested":{"a":"b"}}', $actual);
    }

    /**
     * Test that toJson returns "{}" when given an array containing invalid UTF-8.
     */
    public function testToJsonConvertsArrayWithInvalidUtf8ToEmptyObject(): void
    {
        $invalidArray = [
            'valid' => 'test',
            'invalid' => "\x80\x81\x82",
        ];

        $actual = Cast::toJson($invalidArray);

        self::assertSame('{}', $actual);
    }

    /**
     * Test that toJson returns "{}" when given an object whose toArray returns an array with invalid UTF-8.
     */
    public function testToJsonConvertsObjectWithToArrayReturningInvalidUtf8ToEmptyObject(): void
    {
        $object = new class() {
            /**
             * @return array<string, mixed>
             */
            public function toArray(): array
            {
                return [
                    'invalid' => "\x80\x81\x82",
                ];
            }
        };

        $actual = Cast::toJson($object);

        self::assertSame('{}', $actual);
    }

    /**
     * Test that toJson returns "{}" when given a JsonSerializable object serializing invalid UTF-8.
     */
    public function testToJsonConvertsJsonSerializableWithInvalidUtf8ToEmptyObject(): void
    {
        $object = new class() implements \JsonSerializable {
            /**
             * @return array<string, mixed>
             */
            public function jsonSerialize(): array
            {
                return [
                    'invalid' => "\x80\x81\x82",
                ];
            }
        };

        $actual = Cast::toJson($object);

        self::assertSame('{}', $actual);
    }

    /**
     * Test that toJson returns "{}" when given a plain object whose public properties contain invalid UTF-8.
     */
    public function testToJsonConvertsObjectWithInvalidUtf8PropertyToEmptyObject(): void
    {
        $object          = new stdClass();
        $object->valid   = 'test';
        $object->invalid = "\x80\x81\x82";

        $actual = Cast::toJson($object);

        self::assertSame('{}', $actual);
    }

    /**
     * Test that toJson encodes the value when given a float near the maximum finite magnitude.
     */
    public function testToJsonConvertsVeryLargeFloat(): void
    {
        $input  = 1.7976931348623157E+308;
        $actual = Cast::toJson($input);

        self::assertStringContainsString('1.7976931348623157', $actual);
    }

    /**
     * Test that toJson encodes the value when given a float near the smallest normal magnitude.
     */
    public function testToJsonConvertsVerySmallFloat(): void
    {
        $input  = 2.2250738585072014E-308;
        $actual = Cast::toJson($input);

        self::assertStringContainsString('2.2250738585072014', $actual);
    }

    /**
     * Test that toJson encodes a flat structure successfully when given the minimum depth of one.
     */
    public function testToJsonWorksWithMinimalDepth(): void
    {
        $input  = [
            'key' => 'value',
        ];
        $actual = Cast::toJson($input, JSON_THROW_ON_ERROR, 1);

        self::assertSame('{"key":"value"}', $actual);
    }

    /**
     * Test that toJson returns "{}" when given a string with invalid UTF-8 and custom flags.
     */
    public function testToJsonConvertsStringWithInvalidUtf8AndCustomFlagsToEmptyObject(): void
    {
        $invalidUtf8 = "\x80\x81\x82\x83";

        $actual = Cast::toJson($invalidUtf8, 0, 512);

        self::assertSame('{}', $actual);
    }

    /**
     * Test that toJson returns "{}" when given an array with invalid UTF-8 and custom flags.
     */
    public function testToJsonConvertsArrayWithInvalidUtf8AndCustomFlagsToEmptyObject(): void
    {
        $invalidArray = [
            'test' => "\x80\x81\x82",
        ];

        $actual = Cast::toJson($invalidArray, 0, 512);

        self::assertSame('{}', $actual);
    }

    /**
     * Test that toJson encodes the number when given a float with custom flags of zero.
     */
    public function testToJsonHandlesFloatEncodingWithCustomFlags(): void
    {
        $input = 3.14;

        $actual = Cast::toJson($input, 0, 512);
        self::assertSame('3.14', $actual);
    }

    /**
     * Test that toJson returns "true" when given the boolean true with custom flags of zero.
     */
    public function testToJsonHandlesBoolEncodingWithCustomFlags(): void
    {
        $actual = Cast::toJson(true, 0, 512);
        self::assertSame('true', $actual);
    }

    /**
     * Test that toJson returns the number literal when given an integer with custom flags of zero.
     */
    public function testToJsonHandlesIntEncodingWithCustomFlags(): void
    {
        $actual = Cast::toJson(42, 0, 512);
        self::assertSame('42', $actual);
    }

    /**
     * Test that toJson returns "{}" when given a JsonSerializable object serializing invalid UTF-8 with custom flags.
     */
    public function testToJsonConvertsJsonSerializableEncodingFailureWithInvalidUtf8ToEmptyObject(): void
    {
        $object = new class() implements \JsonSerializable {
            /**
             * @return array<string, mixed>
             */
            public function jsonSerialize(): array
            {
                return [
                    'invalid' => "\x80\x81\x82",
                ];
            }
        };

        $actual = Cast::toJson($object, 0, 512);

        self::assertSame('{}', $actual);
    }

    /**
     * Test that toJson returns "{}" when given an object whose toArray yields invalid UTF-8 with custom flags.
     */
    public function testToJsonConvertsObjectToArrayEncodingFailureWithInvalidUtf8ToEmptyObject(): void
    {
        $object = new class() {
            /**
             * @return array<string, mixed>
             */
            public function toArray(): array
            {
                return [
                    'invalid' => "\x80\x81\x82",
                ];
            }
        };

        $actual = Cast::toJson($object, 0, 512);

        self::assertSame('{}', $actual);
    }

    /**
     * Test that toJson returns "{}" when given a plain object with invalid UTF-8 properties and custom flags.
     */
    public function testToJsonConvertsObjectVarsEncodingFailureWithInvalidUtf8ToEmptyObject(): void
    {
        $object          = new stdClass();
        $object->invalid = "\x80\x81\x82";

        $actual = Cast::toJson($object, 0, 512);

        self::assertSame('{}', $actual);
    }

    /**
     * Test that toJson returns the quoted string when given a valid string with custom flags of zero.
     */
    public function testToJsonHandlesValidStringWithCustomFlags(): void
    {
        $actual = Cast::toJson('hello', 0, 512);
        self::assertSame('"hello"', $actual);
    }

    /**
     * Test that toJson encodes the array when given a valid array without the JSON_THROW_ON_ERROR flag.
     */
    public function testToJsonHandlesValidArrayWithoutThrowFlag(): void
    {
        $actual = Cast::toJson([
            'key' => 'value',
        ], 0, 512);
        self::assertSame('{"key":"value"}', $actual);
    }

    /**
     * Test that toJson encodes the object when given a valid JsonSerializable without the JSON_THROW_ON_ERROR flag.
     */
    public function testToJsonHandlesValidJsonSerializableWithoutThrowFlag(): void
    {
        $object = new class() implements \JsonSerializable {
            /**
             * @return array<string, mixed>
             */
            public function jsonSerialize(): array
            {
                return [
                    'valid' => 'data',
                ];
            }
        };

        $actual = Cast::toJson($object, 0, 512);
        self::assertSame('{"valid":"data"}', $actual);
    }

    /**
     * Test that toJson encodes the object when given a valid toArray object without the JSON_THROW_ON_ERROR flag.
     */
    public function testToJsonHandlesValidObjectToArrayWithoutThrowFlag(): void
    {
        $object = new class() {
            /**
             * @return array<string, mixed>
             */
            public function toArray(): array
            {
                return [
                    'valid' => 'data',
                ];
            }
        };

        $actual = Cast::toJson($object, 0, 512);
        self::assertSame('{"valid":"data"}', $actual);
    }

    /**
     * Test that toJson encodes the public properties when given a valid plain object without the JSON_THROW_ON_ERROR flag.
     */
    public function testToJsonHandlesValidPlainObjectWithoutThrowFlag(): void
    {
        $object        = new stdClass();
        $object->valid = 'data';

        $actual = Cast::toJson($object, 0, 512);
        self::assertSame('{"valid":"data"}', $actual);
    }

    /**
     * Test that toJson returns the scalar literal when given a JsonSerializable object whose jsonSerialize returns a scalar.
     */
    public function testToJsonEncodesJsonSerializableReturningScalarValue(): void
    {
        $object = new class() implements \JsonSerializable {
            public function jsonSerialize(): int
            {
                return 7;
            }
        };

        $actual = Cast::toJson($object);

        self::assertSame('7', $actual);
    }

    /**
     * Test that toJson encodes successfully when given a nested array exactly at the configured depth.
     */
    public function testToJsonEncodesArrayExactlyAtConfiguredDepth(): void
    {
        $input = [
            'a' => [
                'b' => 'c',
            ],
        ];

        $actual = Cast::toJson($input, JSON_THROW_ON_ERROR, 2);

        self::assertSame('{"a":{"b":"c"}}', $actual);
    }

    /**
     * Test that toJson encodes the value when given a large but finite float.
     */
    public function testToJsonEncodesLargeButFiniteFloat(): void
    {
        $input  = 1.0e100;
        $actual = Cast::toJson($input);

        self::assertStringContainsString('1.0e+100', strtolower($actual));
    }
}
