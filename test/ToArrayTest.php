<?php
declare(strict_types=1);

namespace CtwTest\Cast;

use ArrayObject;
use Ctw\Cast\Cast;
use Generator;
use PHPUnit\Framework\TestCase;
use stdClass;
use Traversable;

final class ToArrayTest extends TestCase
{
    /**
     * Test that array value is returned unchanged
     */
    public function testToArrayReturnsArrayValueUnchanged(): void
    {
        $input  = [1, 2, 3];
        $actual = Cast::toArray($input);

        self::assertSame([1, 2, 3], $actual);
    }

    /**
     * Test that empty array is returned unchanged
     */
    public function testToArrayReturnsEmptyArrayUnchanged(): void
    {
        $input  = [];
        $actual = Cast::toArray($input);

        self::assertSame([], $actual);
    }

    /**
     * Test that associative array is returned unchanged
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
     * Test that null is converted to empty array
     */
    public function testToArrayConvertsNullToEmptyArray(): void
    {
        $input  = null;
        $actual = Cast::toArray($input);

        self::assertSame([], $actual);
    }

    /**
     * Test that empty string is converted to empty array
     */
    public function testToArrayConvertsEmptyStringToEmptyArray(): void
    {
        $input  = '';
        $actual = Cast::toArray($input);

        self::assertSame([], $actual);
    }

    /**
     * Test that whitespace only string is converted to empty array
     */
    public function testToArrayConvertsWhitespaceOnlyStringToEmptyArray(): void
    {
        $input  = '   ';
        $actual = Cast::toArray($input);

        self::assertSame([], $actual);
    }

    /**
     * Test that JSON array string is parsed
     */
    public function testToArrayParsesJsonArrayString(): void
    {
        $input  = '["apple", "banana", "cherry"]';
        $actual = Cast::toArray($input);

        self::assertSame(['apple', 'banana', 'cherry'], $actual);
    }

    /**
     * Test that JSON object string is parsed
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
     * Test that nested JSON is parsed
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
     * Test that invalid JSON string is wrapped in array
     */
    public function testToArrayWrapsInvalidJsonStringInArray(): void
    {
        $input  = '{"invalid json}';
        $actual = Cast::toArray($input);

        self::assertSame(['{"invalid json}'], $actual);
    }

    /**
     * Test that regular string is wrapped in array
     */
    public function testToArrayWrapsRegularStringInArray(): void
    {
        $input  = 'hello world';
        $actual = Cast::toArray($input);

        self::assertSame(['hello world'], $actual);
    }

    /**
     * Test that numeric string is wrapped in array
     */
    public function testToArrayWrapsNumericStringInArray(): void
    {
        $input  = '42';
        $actual = Cast::toArray($input);

        self::assertSame(['42'], $actual);
    }

    /**
     * Test that string starting with bracket but not JSON is wrapped in array
     */
    public function testToArrayWrapsStringStartingWithBracketButNotJson(): void
    {
        $input  = '[not json';
        $actual = Cast::toArray($input);

        self::assertSame(['[not json'], $actual);
    }

    /**
     * Test that string starting with brace but not JSON is wrapped in array
     */
    public function testToArrayWrapsStringStartingWithBraceButNotJson(): void
    {
        $input  = '{not json';
        $actual = Cast::toArray($input);

        self::assertSame(['{not json'], $actual);
    }

    /**
     * Test that integer is wrapped in array
     */
    public function testToArrayWrapsIntegerInArray(): void
    {
        $input  = 42;
        $actual = Cast::toArray($input);

        self::assertSame([42], $actual);
    }

    /**
     * Test that zero is wrapped in array
     */
    public function testToArrayWrapsZeroInArray(): void
    {
        $input  = 0;
        $actual = Cast::toArray($input);

        self::assertSame([0], $actual);
    }

    /**
     * Test that negative integer is wrapped in array
     */
    public function testToArrayWrapsNegativeIntegerInArray(): void
    {
        $input  = -42;
        $actual = Cast::toArray($input);

        self::assertSame([-42], $actual);
    }

    /**
     * Test that float is wrapped in array
     */
    public function testToArrayWrapsFloatInArray(): void
    {
        $input  = 3.14;
        $actual = Cast::toArray($input);

        self::assertSame([3.14], $actual);
    }

    /**
     * Test that true boolean is wrapped in array
     */
    public function testToArrayWrapsTrueBooleanInArray(): void
    {
        $input  = true;
        $actual = Cast::toArray($input);

        self::assertSame([true], $actual);
    }

    /**
     * Test that false boolean is wrapped in array
     */
    public function testToArrayWrapsFalseBooleanInArray(): void
    {
        $input  = false;
        $actual = Cast::toArray($input);

        self::assertSame([false], $actual);
    }

    /**
     * Test that Traversable object is converted to array
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
     * Test that ArrayObject is converted to array
     */
    public function testToArrayConvertsArrayObjectToArray(): void
    {
        $input  = new ArrayObject(['apple', 'banana', 'cherry']);
        $actual = Cast::toArray($input);

        self::assertSame(['apple', 'banana', 'cherry'], $actual);
    }

    /**
     * Test that object with toArray method is converted
     */
    public function testToArrayConvertsObjectWithToArrayMethod(): void
    {
        $object = new class() {
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
     * Test that object with public properties is converted
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
     * Test that stdClass object is converted to array
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
     * Test that empty object is converted to empty array
     */
    public function testToArrayConvertsEmptyObjectToEmptyArray(): void
    {
        $object = new stdClass();
        $actual = Cast::toArray($object);

        self::assertSame([], $actual);
    }

    /**
     * Test that JSON with whitespace is parsed
     */
    public function testToArrayParsesJsonWithWhitespace(): void
    {
        $input  = '  ["apple", "banana"]  ';
        $actual = Cast::toArray($input);

        self::assertSame(['apple', 'banana'], $actual);
    }

    /**
     * Test that JSON empty array is parsed
     */
    public function testToArrayParsesJsonEmptyArray(): void
    {
        $input  = '[]';
        $actual = Cast::toArray($input);

        self::assertSame([], $actual);
    }

    /**
     * Test that JSON empty object is parsed
     */
    public function testToArrayParsesJsonEmptyObject(): void
    {
        $input  = '{}';
        $actual = Cast::toArray($input);

        self::assertSame([], $actual);
    }

    /**
     * Test that multidimensional array is returned unchanged
     */
    public function testToArrayReturnsMultidimensionalArrayUnchanged(): void
    {
        $input  = [[1, 2], [3, 4]];
        $actual = Cast::toArray($input);

        self::assertSame([[1, 2], [3, 4]], $actual);
    }

    /**
     * Test that string with special characters is wrapped in array
     */
    public function testToArrayWrapsStringWithSpecialCharactersInArray(): void
    {
        $input  = "hello\nworld\ttab";
        $actual = Cast::toArray($input);

        self::assertSame(["hello\nworld\ttab"], $actual);
    }

    /**
     * Test that unicode string is wrapped in array
     */
    public function testToArrayWrapsUnicodeStringInArray(): void
    {
        $input  = '你好世界 🌍';
        $actual = Cast::toArray($input);

        self::assertSame(['你好世界 🌍'], $actual);
    }

    /**
     * Test that JSON with unicode is parsed
     */
    public function testToArrayParsesJsonWithUnicode(): void
    {
        $input  = '["你好", "世界"]';
        $actual = Cast::toArray($input);

        self::assertSame(['你好', '世界'], $actual);
    }

    /**
     * Test that object falls back to get_object_vars when toArray returns non-array
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
     * Test that empty array is returned for object with no public properties
     */
    public function testToArrayReturnsEmptyArrayForObjectWithNoPublicProperties(): void
    {
        $object = new class() {
        };

        $actual = Cast::toArray($object);

        self::assertSame([], $actual);
    }

    /**
     * Test that Generator is converted to array
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
     * Test that Generator with keys is converted to array
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
     * Test that mixed array is returned unchanged
     */
    public function testToArrayReturnsMixedArrayUnchanged(): void
    {
        $input  = [1, 'hello', 3.14, true, null];
        $actual = Cast::toArray($input);

        self::assertSame([1, 'hello', 3.14, true, null], $actual);
    }

    /**
     * Test that JSON with null values is parsed
     */
    public function testToArrayParsesJsonWithNullValues(): void
    {
        $input  = '["value", null, "another"]';
        $actual = Cast::toArray($input);

        self::assertSame(['value', null, 'another'], $actual);
    }

    /**
     * Test that JSON with boolean values is parsed
     */
    public function testToArrayParsesJsonWithBooleanValues(): void
    {
        $input  = '[true, false, true]';
        $actual = Cast::toArray($input);

        self::assertSame([true, false, true], $actual);
    }

    /**
     * Test that very large array is returned unchanged
     */
    public function testToArrayReturnsVeryLargeArrayUnchanged(): void
    {
        $input  = range(1, 1000);
        $actual = Cast::toArray($input);

        self::assertSame($input, $actual);
    }

    /**
     * Test that string that looks like JSON but does not start with bracket is wrapped
     */
    public function testToArrayWrapsStringThatLooksLikeJsonButDoesNotStartWithBracket(): void
    {
        $input  = 'not ["a", "b"]';
        $actual = Cast::toArray($input);

        self::assertSame(['not ["a", "b"]'], $actual);
    }

    /**
     * Test that tab character string is converted to empty array
     */
    public function testToArrayConvertsTabCharacterStringToEmptyArray(): void
    {
        $input  = "\t";
        $actual = Cast::toArray($input);

        self::assertSame([], $actual);
    }

    /**
     * Test that newline character string is converted to empty array
     */
    public function testToArrayConvertsNewlineCharacterStringToEmptyArray(): void
    {
        $input  = "\n";
        $actual = Cast::toArray($input);

        self::assertSame([], $actual);
    }

    /**
     * Test that JSON with numeric keys is parsed
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
     * Test that deeply nested JSON is parsed
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
     * Test that JSON with mixed types is parsed
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
}
