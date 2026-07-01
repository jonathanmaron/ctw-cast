<?php
declare(strict_types=1);

namespace CtwTest\Cast;

use ArrayObject;
use Ctw\Cast\Cast;
use Generator;
use PHPUnit\Framework\TestCase;
use stdClass;

final class ToArrayTest extends TestCase
{
    /**
     * Test that toArray returns the array unchanged when given an indexed array.
     */
    public function testToArrayReturnsArrayValueUnchanged(): void
    {
        $input  = [1, 2, 3];
        $actual = Cast::toArray($input);

        self::assertSame([1, 2, 3], $actual);
    }

    /**
     * Test that toArray returns an empty array unchanged when given an empty array.
     */
    public function testToArrayReturnsEmptyArrayUnchanged(): void
    {
        $input  = [];
        $actual = Cast::toArray($input);

        self::assertSame([], $actual);
    }

    /**
     * Test that toArray returns the array unchanged when given an associative array.
     */
    public function testToArrayReturnsAssociativeArrayUnchanged(): void
    {
        $input  = [
            'key' => 'value',
            'foo' => 'bar',
        ];
        $actual = Cast::toArray($input);

        self::assertSame([
            'key' => 'value',
            'foo' => 'bar',
        ], $actual);
    }

    /**
     * Test that toArray returns an empty array when given null.
     */
    public function testToArrayConvertsNullToEmptyArray(): void
    {
        $input  = null;
        $actual = Cast::toArray($input);

        self::assertSame([], $actual);
    }

    /**
     * Test that toArray returns an empty array when given an empty string.
     */
    public function testToArrayConvertsEmptyStringToEmptyArray(): void
    {
        $input  = '';
        $actual = Cast::toArray($input);

        self::assertSame([], $actual);
    }

    /**
     * Test that toArray returns an empty array when given a string containing only whitespace.
     */
    public function testToArrayConvertsWhitespaceOnlyStringToEmptyArray(): void
    {
        $input  = '   ';
        $actual = Cast::toArray($input);

        self::assertSame([], $actual);
    }

    /**
     * Test that toArray returns the decoded array when given a JSON array string.
     */
    public function testToArrayParsesJsonArrayString(): void
    {
        $input  = '["apple", "banana", "cherry"]';
        $actual = Cast::toArray($input);

        self::assertSame(['apple', 'banana', 'cherry'], $actual);
    }

    /**
     * Test that toArray returns the decoded associative array when given a JSON object string.
     */
    public function testToArrayParsesJsonObjectString(): void
    {
        $input  = '{"name": "John", "age": 30}';
        $actual = Cast::toArray($input);

        self::assertSame([
            'name' => 'John',
            'age'  => 30,
        ], $actual);
    }

    /**
     * Test that toArray returns the fully decoded structure when given a nested JSON object string.
     */
    public function testToArrayParsesNestedJson(): void
    {
        $input  = '{"user": {"name": "John", "age": 30}}';
        $actual = Cast::toArray($input);

        self::assertSame([
            'user' => [
                'name' => 'John',
                'age'  => 30,
            ],
        ], $actual);
    }

    /**
     * Test that toArray wraps the original string when given a bracketed string that is invalid JSON.
     */
    public function testToArrayWrapsInvalidJsonStringInArray(): void
    {
        $input  = '{"invalid json}';
        $actual = Cast::toArray($input);

        self::assertSame(['{"invalid json}'], $actual);
    }

    /**
     * Test that toArray wraps the string when given a plain non-JSON string.
     */
    public function testToArrayWrapsRegularStringInArray(): void
    {
        $input  = 'hello world';
        $actual = Cast::toArray($input);

        self::assertSame(['hello world'], $actual);
    }

    /**
     * Test that toArray wraps the string when given a numeric string.
     */
    public function testToArrayWrapsNumericStringInArray(): void
    {
        $input  = '42';
        $actual = Cast::toArray($input);

        self::assertSame(['42'], $actual);
    }

    /**
     * Test that toArray wraps the string when given a string that opens with a bracket but is not JSON.
     */
    public function testToArrayWrapsStringStartingWithBracketButNotJson(): void
    {
        $input  = '[not json';
        $actual = Cast::toArray($input);

        self::assertSame(['[not json'], $actual);
    }

    /**
     * Test that toArray wraps the string when given a string that opens with a brace but is not JSON.
     */
    public function testToArrayWrapsStringStartingWithBraceButNotJson(): void
    {
        $input  = '{not json';
        $actual = Cast::toArray($input);

        self::assertSame(['{not json'], $actual);
    }

    /**
     * Test that toArray wraps the value in a single-element array when given an integer.
     */
    public function testToArrayWrapsIntegerInArray(): void
    {
        $input  = 42;
        $actual = Cast::toArray($input);

        self::assertSame([42], $actual);
    }

    /**
     * Test that toArray wraps the value in a single-element array when given the integer zero.
     */
    public function testToArrayWrapsZeroInArray(): void
    {
        $input  = 0;
        $actual = Cast::toArray($input);

        self::assertSame([0], $actual);
    }

    /**
     * Test that toArray wraps the value in a single-element array when given a negative integer.
     */
    public function testToArrayWrapsNegativeIntegerInArray(): void
    {
        $input  = -42;
        $actual = Cast::toArray($input);

        self::assertSame([-42], $actual);
    }

    /**
     * Test that toArray wraps the value in a single-element array when given a float.
     */
    public function testToArrayWrapsFloatInArray(): void
    {
        $input  = 3.14;
        $actual = Cast::toArray($input);

        self::assertSame([3.14], $actual);
    }

    /**
     * Test that toArray wraps the value in a single-element array when given the boolean true.
     */
    public function testToArrayWrapsTrueBooleanInArray(): void
    {
        $input  = true;
        $actual = Cast::toArray($input);

        self::assertSame([true], $actual);
    }

    /**
     * Test that toArray wraps the value in a single-element array when given the boolean false.
     */
    public function testToArrayWrapsFalseBooleanInArray(): void
    {
        $input  = false;
        $actual = Cast::toArray($input);

        self::assertSame([false], $actual);
    }

    /**
     * Test that toArray materializes the yielded values when given a Traversable object.
     */
    public function testToArrayConvertsTraversableObjectToArray(): void
    {
        $generator = function (): Generator {
            yield 1;
            yield 2;
            yield 3;
        };

        $actual = Cast::toArray($generator());

        self::assertSame([1, 2, 3], $actual);
    }

    /**
     * Test that toArray materializes the contents when given an ArrayObject.
     */
    public function testToArrayConvertsArrayObjectToArray(): void
    {
        $input  = new ArrayObject(['apple', 'banana', 'cherry']);
        $actual = Cast::toArray($input);

        self::assertSame(['apple', 'banana', 'cherry'], $actual);
    }

    /**
     * Test that toArray returns the method result when given an object exposing a toArray method.
     */
    public function testToArrayConvertsObjectWithToArrayMethod(): void
    {
        $object = new class() {
            /**
             * @return array<string, mixed>
             */
            public function toArray(): array
            {
                return [
                    'name' => 'John',
                    'age'  => 30,
                ];
            }
        };

        $actual = Cast::toArray($object);

        self::assertSame([
            'name' => 'John',
            'age'  => 30,
        ], $actual);
    }

    /**
     * Test that toArray extracts the public properties when given an object without a toArray method.
     */
    public function testToArrayConvertsObjectWithPublicProperties(): void
    {
        $object = new class() {
            public string $name = 'John';

            public int $age  = 30;
        };

        $actual = Cast::toArray($object);

        self::assertSame([
            'name' => 'John',
            'age'  => 30,
        ], $actual);
    }

    /**
     * Test that toArray extracts the public properties when given a populated stdClass object.
     */
    public function testToArrayConvertsStdClassObjectToArray(): void
    {
        $object       = new stdClass();
        $object->name = 'John';
        $object->age  = 30;

        $actual = Cast::toArray($object);

        self::assertSame([
            'name' => 'John',
            'age'  => 30,
        ], $actual);
    }

    /**
     * Test that toArray returns an empty array when given an empty stdClass object.
     */
    public function testToArrayConvertsEmptyObjectToEmptyArray(): void
    {
        $object = new stdClass();
        $actual = Cast::toArray($object);

        self::assertSame([], $actual);
    }

    /**
     * Test that toArray trims and decodes when given a JSON array string padded with whitespace.
     */
    public function testToArrayParsesJsonWithWhitespace(): void
    {
        $input  = '  ["apple", "banana"]  ';
        $actual = Cast::toArray($input);

        self::assertSame(['apple', 'banana'], $actual);
    }

    /**
     * Test that toArray returns an empty array when given the JSON empty array string.
     */
    public function testToArrayParsesJsonEmptyArray(): void
    {
        $input  = '[]';
        $actual = Cast::toArray($input);

        self::assertSame([], $actual);
    }

    /**
     * Test that toArray returns an empty array when given the JSON empty object string.
     */
    public function testToArrayParsesJsonEmptyObject(): void
    {
        $input  = '{}';
        $actual = Cast::toArray($input);

        self::assertSame([], $actual);
    }

    /**
     * Test that toArray returns the array unchanged when given a multidimensional array.
     */
    public function testToArrayReturnsMultidimensionalArrayUnchanged(): void
    {
        $input  = [[1, 2], [3, 4]];
        $actual = Cast::toArray($input);

        self::assertSame([[1, 2], [3, 4]], $actual);
    }

    /**
     * Test that toArray wraps the string when given a string containing control characters.
     */
    public function testToArrayWrapsStringWithSpecialCharactersInArray(): void
    {
        $input  = "hello\nworld\ttab";
        $actual = Cast::toArray($input);

        self::assertSame(["hello\nworld\ttab"], $actual);
    }

    /**
     * Test that toArray wraps the string when given a Unicode string.
     */
    public function testToArrayWrapsUnicodeStringInArray(): void
    {
        $input  = '你好世界 🌍';
        $actual = Cast::toArray($input);

        self::assertSame(['你好世界 🌍'], $actual);
    }

    /**
     * Test that toArray preserves multibyte characters when given a JSON array of Unicode strings.
     */
    public function testToArrayParsesJsonWithUnicode(): void
    {
        $input  = '["你好", "世界"]';
        $actual = Cast::toArray($input);

        self::assertSame(['你好', '世界'], $actual);
    }

    /**
     * Test that toArray falls back to public properties when given an object whose toArray returns a non-array.
     */
    public function testToArrayFallsBackToGetObjectVarsWhenToArrayReturnsNonArray(): void
    {
        $object = new class() {
            public string $name = 'John';

            public function toArray(): string
            {
                return 'not an array';
            }
        };

        $actual = Cast::toArray($object);

        self::assertSame([
            'name' => 'John',
        ], $actual);
    }

    /**
     * Test that toArray returns an empty array when given an object with no public properties.
     */
    public function testToArrayReturnsEmptyArrayForObjectWithNoPublicProperties(): void
    {
        $object = new class() {
        };

        $actual = Cast::toArray($object);

        self::assertSame([], $actual);
    }

    /**
     * Test that toArray materializes the yielded values when given a Generator without keys.
     */
    public function testToArrayConvertsGeneratorToArray(): void
    {
        $generator = (function (): Generator {
            yield 'apple';
            yield 'banana';
            yield 'cherry';
        })();

        $actual = Cast::toArray($generator);

        self::assertSame(['apple', 'banana', 'cherry'], $actual);
    }

    /**
     * Test that toArray preserves the keys when given a Generator that yields key-value pairs.
     */
    public function testToArrayConvertsGeneratorWithKeysToArray(): void
    {
        $generator = (function (): Generator {
            yield 'a' => 'apple';
            yield 'b' => 'banana';
            yield 'c' => 'cherry';
        })();

        $actual = Cast::toArray($generator);

        self::assertSame([
            'a' => 'apple',
            'b' => 'banana',
            'c' => 'cherry',
        ], $actual);
    }

    /**
     * Test that toArray returns the array unchanged when given an array of mixed scalar types.
     */
    public function testToArrayReturnsMixedArrayUnchanged(): void
    {
        $input  = [1, 'hello', 3.14, true, null];
        $actual = Cast::toArray($input);

        self::assertSame([1, 'hello', 3.14, true, null], $actual);
    }

    /**
     * Test that toArray preserves nulls when given a JSON array containing null values.
     */
    public function testToArrayParsesJsonWithNullValues(): void
    {
        $input  = '["value", null, "another"]';
        $actual = Cast::toArray($input);

        self::assertSame(['value', null, 'another'], $actual);
    }

    /**
     * Test that toArray preserves booleans when given a JSON array containing boolean values.
     */
    public function testToArrayParsesJsonWithBooleanValues(): void
    {
        $input  = '[true, false, true]';
        $actual = Cast::toArray($input);

        self::assertSame([true, false, true], $actual);
    }

    /**
     * Test that toArray returns the array unchanged when given a very large array.
     */
    public function testToArrayReturnsVeryLargeArrayUnchanged(): void
    {
        $input  = range(1, 1000);
        $actual = Cast::toArray($input);

        self::assertSame($input, $actual);
    }

    /**
     * Test that toArray wraps the string when given a string containing JSON that does not start at the first character.
     */
    public function testToArrayWrapsStringThatLooksLikeJsonButDoesNotStartWithBracket(): void
    {
        $input  = 'not ["a", "b"]';
        $actual = Cast::toArray($input);

        self::assertSame(['not ["a", "b"]'], $actual);
    }

    /**
     * Test that toArray returns an empty array when given a string containing only a tab character.
     */
    public function testToArrayConvertsTabCharacterStringToEmptyArray(): void
    {
        $input  = "\t";
        $actual = Cast::toArray($input);

        self::assertSame([], $actual);
    }

    /**
     * Test that toArray returns an empty array when given a string containing only a newline character.
     */
    public function testToArrayConvertsNewlineCharacterStringToEmptyArray(): void
    {
        $input  = "\n";
        $actual = Cast::toArray($input);

        self::assertSame([], $actual);
    }

    /**
     * Test that toArray preserves string keys when given a JSON object with numeric-looking keys.
     */
    public function testToArrayParsesJsonWithNumericKeys(): void
    {
        $input  = '{"0": "zero", "1": "one", "2": "two"}';
        $actual = Cast::toArray($input);

        self::assertSame([
            '0' => 'zero',
            '1' => 'one',
            '2' => 'two',
        ], $actual);
    }

    /**
     * Test that toArray decodes the entire structure when given a deeply nested JSON object string.
     */
    public function testToArrayParsesDeeplyNestedJson(): void
    {
        $input  = '{"a": {"b": {"c": {"d": "deep"}}}}';
        $actual = Cast::toArray($input);

        self::assertSame([
            'a' => [
                'b' => [
                    'c' => [
                        'd' => 'deep',
                    ],
                ],
            ],
        ], $actual);
    }

    /**
     * Test that toArray preserves each value's type when given a JSON object with mixed value types.
     */
    public function testToArrayParsesJsonWithMixedTypes(): void
    {
        $input  = '{"int": 42, "float": 3.14, "bool": true, "null": null, "string": "text"}';
        $actual = Cast::toArray($input);

        self::assertSame([
            'int'    => 42,
            'float'  => 3.14,
            'bool'   => true,
            'null'   => null,
            'string' => 'text',
        ], $actual);
    }

    /**
     * Test that toArray returns an empty array when given an open resource.
     */
    public function testToArrayConvertsResourceToEmptyArray(): void
    {
        $resource = fopen('php://memory', 'r');
        self::assertIsResource($resource);

        try {
            $actual = Cast::toArray($resource);
        } finally {
            fclose($resource);
        }

        self::assertSame([], $actual);
    }

    /**
     * Test that toArray returns the decoded array when given a JSON array that contains a single scalar.
     *
     * The decoded value is an array, so it is returned directly rather than being
     * wrapped again in an outer array.
     */
    public function testToArrayWrapsJsonStringDecodingToScalar(): void
    {
        $input  = '[123]';
        $actual = Cast::toArray($input);

        self::assertSame([123], $actual);
    }

    /**
     * Test that toArray wraps the original string when given a bracketed string whose JSON decodes to a non-array.
     */
    public function testToArrayWrapsJsonStringStartingWithBracketAndDecodingToNull(): void
    {
        $input  = '[null][';
        $actual = Cast::toArray($input);

        self::assertSame(['[null]['], $actual);
    }

    /**
     * Test that toArray returns an empty array when given a closed resource.
     */
    public function testToArrayConvertsClosedResourceToEmptyArray(): void
    {
        $resource = fopen('php://memory', 'r');
        self::assertIsResource($resource);
        fclose($resource);

        $actual = Cast::toArray($resource);

        self::assertSame([], $actual);
    }
}
