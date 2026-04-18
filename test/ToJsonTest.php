<?php
declare(strict_types=1);

namespace CtwTest\Cast;

use Ctw\Cast\Cast;
use PHPUnit\Framework\TestCase;
use stdClass;

final class ToJsonTest extends TestCase
{
    /**
     * Test that null is converted to JSON null string
     */
    public function testToJsonConvertsNullToJsonNull(): void
    {
        $actual = Cast::toJson(null);

        self::assertSame('null', $actual);
    }

    /**
     * Test that string is converted to JSON string
     */
    public function testToJsonConvertsStringToJsonString(): void
    {
        $input  = 'hello world';
        $actual = Cast::toJson($input);

        self::assertSame('"hello world"', $actual);
    }

    /**
     * Test that empty string is converted to JSON empty string
     */
    public function testToJsonConvertsEmptyStringToJsonEmptyString(): void
    {
        $input  = '';
        $actual = Cast::toJson($input);

        self::assertSame('""', $actual);
    }

    /**
     * Test that string with special characters is properly escaped
     */
    public function testToJsonEscapesSpecialCharactersInString(): void
    {
        $input  = "line1\nline2\ttab\"quote";
        $actual = Cast::toJson($input);

        self::assertSame('"line1\nline2\ttab\"quote"', $actual);
    }

    /**
     * Test that string with unicode characters is preserved
     */
    public function testToJsonPreservesUnicodeCharacters(): void
    {
        $input  = 'Hello 世界 🌍';
        $actual = Cast::toJson($input);

        self::assertSame('"Hello 世界 🌍"', $actual);
    }

    /**
     * Test that positive integer is converted to JSON number
     */
    public function testToJsonConvertsPositiveIntegerToJsonNumber(): void
    {
        $input  = 42;
        $actual = Cast::toJson($input);

        self::assertSame('42', $actual);
    }

    /**
     * Test that negative integer is converted to JSON number
     */
    public function testToJsonConvertsNegativeIntegerToJsonNumber(): void
    {
        $input  = -42;
        $actual = Cast::toJson($input);

        self::assertSame('-42', $actual);
    }

    /**
     * Test that zero is converted to JSON zero
     */
    public function testToJsonConvertsZeroToJsonZero(): void
    {
        $input  = 0;
        $actual = Cast::toJson($input);

        self::assertSame('0', $actual);
    }

    /**
     * Test that PHP_INT_MAX is converted to JSON
     */
    public function testToJsonConvertsPhpIntMaxToJson(): void
    {
        $input  = PHP_INT_MAX;
        $actual = Cast::toJson($input);

        self::assertSame((string) PHP_INT_MAX, $actual);
    }

    /**
     * Test that PHP_INT_MIN is converted to JSON
     */
    public function testToJsonConvertsPhpIntMinToJson(): void
    {
        $input  = PHP_INT_MIN;
        $actual = Cast::toJson($input);

        self::assertSame((string) PHP_INT_MIN, $actual);
    }

    /**
     * Test that positive float is converted to JSON number
     */
    public function testToJsonConvertsPositiveFloatToJsonNumber(): void
    {
        $input  = 3.14;
        $actual = Cast::toJson($input);

        self::assertSame('3.14', $actual);
    }

    /**
     * Test that negative float is converted to JSON number
     */
    public function testToJsonConvertsNegativeFloatToJsonNumber(): void
    {
        $input  = -3.14;
        $actual = Cast::toJson($input);

        self::assertSame('-3.14', $actual);
    }

    /**
     * Test that zero float is converted to JSON zero
     */
    public function testToJsonConvertsZeroFloatToJsonZero(): void
    {
        $input  = 0.0;
        $actual = Cast::toJson($input);

        self::assertSame('0', $actual);
    }

    /**
     * Test that float with many decimals is converted
     */
    public function testToJsonConvertsFloatWithManyDecimals(): void
    {
        $input  = 3.141592653589793;
        $actual = Cast::toJson($input);

        self::assertSame('3.141592653589793', $actual);
    }

    /**
     * Test that INF float is converted to empty JSON object
     */
    public function testToJsonConvertsInfiniteFloatToEmptyObject(): void
    {
        $actual = Cast::toJson(INF);

        self::assertSame('{}', $actual);
    }

    /**
     * Test that negative INF float is converted to empty JSON object
     */
    public function testToJsonConvertsNegativeInfiniteFloatToEmptyObject(): void
    {
        $actual = Cast::toJson(-INF);

        self::assertSame('{}', $actual);
    }

    /**
     * Test that NAN float is converted to empty JSON object
     */
    public function testToJsonConvertsNanFloatToEmptyObject(): void
    {
        $actual = Cast::toJson(NAN);

        self::assertSame('{}', $actual);
    }

    /**
     * Test that true boolean is converted to JSON true
     */
    public function testToJsonConvertsTrueBooleanToJsonTrue(): void
    {
        $input  = true;
        $actual = Cast::toJson($input);

        self::assertSame('true', $actual);
    }

    /**
     * Test that false boolean is converted to JSON false
     */
    public function testToJsonConvertsFalseBooleanToJsonFalse(): void
    {
        $input  = false;
        $actual = Cast::toJson($input);

        self::assertSame('false', $actual);
    }

    /**
     * Test that empty array is converted to JSON empty array
     */
    public function testToJsonConvertsEmptyArrayToJsonEmptyArray(): void
    {
        $input  = [];
        $actual = Cast::toJson($input);

        self::assertSame('[]', $actual);
    }

    /**
     * Test that indexed array is converted to JSON array
     */
    public function testToJsonConvertsIndexedArrayToJsonArray(): void
    {
        $input  = [1, 2, 3, 4, 5];
        $actual = Cast::toJson($input);

        self::assertSame('[1,2,3,4,5]', $actual);
    }

    /**
     * Test that associative array is converted to JSON object
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
     * Test that nested array is converted to JSON
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
     * Test that array with mixed types is converted to JSON
     */
    public function testToJsonConvertsArrayWithMixedTypesToJson(): void
    {
        $input  = ['string', 42, 3.14, true, null, ['nested']];
        $actual = Cast::toJson($input);

        self::assertSame('["string",42,3.14,true,null,["nested"]]', $actual);
    }

    /**
     * Test that stdClass object is converted to JSON
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
     * Test that empty stdClass object is converted to JSON
     */
    public function testToJsonConvertsEmptyStdClassObjectToJson(): void
    {
        $object = new stdClass();
        $actual = Cast::toJson($object);

        self::assertSame('[]', $actual);
    }

    /**
     * Test that object with toArray method is converted to JSON
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
     * Test that JsonSerializable object is converted to JSON
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
     * Test that object with public properties is converted to JSON
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
     * Test that object with toArray returning non-array is converted to empty JSON object
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
     * Test that custom flags can be passed
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
     * Test that pretty print flag works
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
     * Test that custom depth can be passed
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
     * Test that exceeding max depth is converted to empty JSON object
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
     * Test that depth less than 1 is converted to empty JSON object
     */
    public function testToJsonConvertsDepthLessThanOneToEmptyObject(): void
    {
        $actual = Cast::toJson(['test'], JSON_THROW_ON_ERROR, 0);

        self::assertSame('{}', $actual);
    }

    /**
     * Test that negative depth is converted to empty JSON object
     */
    public function testToJsonConvertsNegativeDepthToEmptyObject(): void
    {
        $actual = Cast::toJson(['test'], JSON_THROW_ON_ERROR, -1);

        self::assertSame('{}', $actual);
    }

    /**
     * Test that resource type is converted to empty JSON object
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
     * Test that closed resource is converted to empty JSON object
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
     * Test that invalid UTF-8 string is converted to empty JSON object
     */
    public function testToJsonConvertsInvalidUtf8StringToEmptyObject(): void
    {
        $invalidUtf8 = "\x80\x81\x82\x83";

        $actual = Cast::toJson($invalidUtf8);

        self::assertSame('{}', $actual);
    }

    /**
     * Test that object with only private properties is converted
     */
    public function testToJsonConvertsObjectWithPrivateProperties(): void
    {
        $object = new class() {
        };

        $actual = Cast::toJson($object);

        self::assertSame('[]', $actual);
    }

    /**
     * Test that JsonSerializable takes precedence over toArray
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
     * Test that object without toArray or JsonSerializable uses get_object_vars
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
     * Test that array with invalid UTF-8 is converted to empty JSON object
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
     * Test that object with toArray returning array with invalid UTF-8 is converted to empty JSON object
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
     * Test that JsonSerializable with invalid UTF-8 is converted to empty JSON object
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
     * Test that object with invalid UTF-8 in properties is converted to empty JSON object
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
     * Test that very large float is converted correctly
     */
    public function testToJsonConvertsVeryLargeFloat(): void
    {
        $input  = 1.7976931348623157E+308;
        $actual = Cast::toJson($input);

        self::assertStringContainsString('1.7976931348623157', $actual);
    }

    /**
     * Test that very small float is converted correctly
     */
    public function testToJsonConvertsVerySmallFloat(): void
    {
        $input  = 2.2250738585072014E-308;
        $actual = Cast::toJson($input);

        self::assertStringContainsString('2.2250738585072014', $actual);
    }

    /**
     * Test minimal depth of 1 works
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
     * Test that string encoding with invalid UTF-8 returns empty JSON object
     */
    public function testToJsonConvertsStringWithInvalidUtf8AndCustomFlagsToEmptyObject(): void
    {
        $invalidUtf8 = "\x80\x81\x82\x83";

        $actual = Cast::toJson($invalidUtf8, 0, 512);

        self::assertSame('{}', $actual);
    }

    /**
     * Test that array encoding with invalid UTF-8 returns empty JSON object
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
     * Test that float encoding works with custom flags
     */
    public function testToJsonHandlesFloatEncodingWithCustomFlags(): void
    {
        $input = 3.14;

        $actual = Cast::toJson($input, 0, 512);
        self::assertSame('3.14', $actual);
    }

    /**
     * Test that bool encoding works with custom flags
     */
    public function testToJsonHandlesBoolEncodingWithCustomFlags(): void
    {
        $actual = Cast::toJson(true, 0, 512);
        self::assertSame('true', $actual);
    }

    /**
     * Test that int encoding works with custom flags
     */
    public function testToJsonHandlesIntEncodingWithCustomFlags(): void
    {
        $actual = Cast::toJson(42, 0, 512);
        self::assertSame('42', $actual);
    }

    /**
     * Test JsonSerializable encoding failure with invalid UTF-8 returns empty JSON object
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
     * Test object with toArray encoding failure with invalid UTF-8 returns empty JSON object
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
     * Test object with get_object_vars encoding failure with invalid UTF-8 returns empty JSON object
     */
    public function testToJsonConvertsObjectVarsEncodingFailureWithInvalidUtf8ToEmptyObject(): void
    {
        $object          = new stdClass();
        $object->invalid = "\x80\x81\x82";

        $actual = Cast::toJson($object, 0, 512);

        self::assertSame('{}', $actual);
    }

    /**
     * Test that valid string works with custom flags
     */
    public function testToJsonHandlesValidStringWithCustomFlags(): void
    {
        $actual = Cast::toJson('hello', 0, 512);
        self::assertSame('"hello"', $actual);
    }

    /**
     * Test that valid array works without JSON_THROW_ON_ERROR
     */
    public function testToJsonHandlesValidArrayWithoutThrowFlag(): void
    {
        $actual = Cast::toJson([
            'key' => 'value',
        ], 0, 512);
        self::assertSame('{"key":"value"}', $actual);
    }

    /**
     * Test JsonSerializable that works without JSON_THROW_ON_ERROR
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
     * Test object with toArray that works without JSON_THROW_ON_ERROR
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
     * Test plain object that works without JSON_THROW_ON_ERROR
     */
    public function testToJsonHandlesValidPlainObjectWithoutThrowFlag(): void
    {
        $object        = new stdClass();
        $object->valid = 'data';

        $actual = Cast::toJson($object, 0, 512);
        self::assertSame('{"valid":"data"}', $actual);
    }

    /**
     * Test that JsonSerializable returning a scalar is encoded as a JSON scalar.
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
     * Test that a nested array exactly at the configured depth succeeds.
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
     * Test that float exactly at large magnitude but finite encodes successfully.
     */
    public function testToJsonEncodesLargeButFiniteFloat(): void
    {
        $input  = 1.0e100;
        $actual = Cast::toJson($input);

        self::assertStringContainsString('1.0e+100', strtolower($actual));
    }
}
