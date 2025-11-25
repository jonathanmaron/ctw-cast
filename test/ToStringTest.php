<?php
declare(strict_types=1);

namespace CtwTest\Cast;

use Ctw\Cast\Cast;
use Ctw\Cast\Exception\CastException;
use PHPUnit\Framework\TestCase;
use stdClass;
use Stringable;

final class ToStringTest extends TestCase
{
    /**
     * Test that string value is returned unchanged
     */
    public function testToStringReturnsStringValueUnchanged(): void
    {
        $input  = 'hello world';
        $actual = Cast::toString($input);

        self::assertSame('hello world', $actual);
    }

    /**
     * Test that empty string is returned unchanged
     */
    public function testToStringReturnsEmptyStringUnchanged(): void
    {
        $input  = '';
        $actual = Cast::toString($input);

        self::assertSame('', $actual);
    }

    /**
     * Test that string with whitespace is preserved
     */
    public function testToStringPreservesWhitespaceInString(): void
    {
        $input  = "  hello  \n  world  ";
        $actual = Cast::toString($input);

        self::assertSame("  hello  \n  world  ", $actual);
    }

    /**
     * Test that positive integer is converted to string
     */
    public function testToStringConvertsPositiveIntegerToString(): void
    {
        $input  = 42;
        $actual = Cast::toString($input);

        self::assertSame('42', $actual);
    }

    /**
     * Test that negative integer is converted to string
     */
    public function testToStringConvertsNegativeIntegerToString(): void
    {
        $input  = -42;
        $actual = Cast::toString($input);

        self::assertSame('-42', $actual);
    }

    /**
     * Test that zero is converted to string
     */
    public function testToStringConvertsZeroToString(): void
    {
        $input  = 0;
        $actual = Cast::toString($input);

        self::assertSame('0', $actual);
    }

    /**
     * Test that positive float is converted to string
     */
    public function testToStringConvertsPositiveFloatToString(): void
    {
        $input  = 3.14;
        $actual = Cast::toString($input);

        self::assertSame('3.14', $actual);
    }

    /**
     * Test that negative float is converted to string
     */
    public function testToStringConvertsNegativeFloatToString(): void
    {
        $input  = -3.14;
        $actual = Cast::toString($input);

        self::assertSame('-3.14', $actual);
    }

    /**
     * Test that zero float is converted to string
     */
    public function testToStringConvertsZeroFloatToString(): void
    {
        $input  = 0.0;
        $actual = Cast::toString($input);

        self::assertSame('0', $actual);
    }

    /**
     * Test that float with scientific notation is converted
     */
    public function testToStringConvertsFloatWithScientificNotation(): void
    {
        $input  = 1.23e-4;
        $actual = Cast::toString($input);

        self::assertStringContainsString('0.000123', $actual);
    }

    /**
     * Test that true boolean is converted to string one
     */
    public function testToStringConvertsTrueBooleanToOne(): void
    {
        $input  = true;
        $actual = Cast::toString($input);

        self::assertSame('1', $actual);
    }

    /**
     * Test that false boolean is converted to string zero
     */
    public function testToStringConvertsFalseBooleanToZero(): void
    {
        $input  = false;
        $actual = Cast::toString($input);

        self::assertSame('0', $actual);
    }

    /**
     * Test that null is converted to empty string
     */
    public function testToStringConvertsNullToEmptyString(): void
    {
        $input  = null;
        $actual = Cast::toString($input);

        self::assertSame('', $actual);
    }

    /**
     * Test that object with __toString method is converted
     */
    public function testToStringConvertsObjectWithToStringMethod(): void
    {
        $object = new class() {
            public function __toString(): string
            {
                return 'custom string';
            }
        };

        $actual = Cast::toString($object);

        self::assertSame('custom string', $actual);
    }

    /**
     * Test that stringable object is converted
     */
    public function testToStringConvertsStringableObject(): void
    {
        $object = new class() implements Stringable {
            public function __toString(): string
            {
                return 'stringable object';
            }
        };

        $actual = Cast::toString($object);

        self::assertSame('stringable object', $actual);
    }

    /**
     * Test that object without __toString method throws exception
     */
    public function testToStringThrowsExceptionForObjectWithoutToStringMethod(): void
    {
        $object = new stdClass();

        $this->expectException(CastException::class);
        $this->expectExceptionMessage('cannot be cast to string');

        Cast::toString($object);
    }

    /**
     * Test that array throws exception
     */
    public function testToStringThrowsExceptionForArray(): void
    {
        $input = ['hello', 'world'];

        $this->expectException(CastException::class);
        $this->expectExceptionMessage('Value of type array cannot be cast to string');

        Cast::toString($input);
    }

    /**
     * Test that empty array throws exception
     */
    public function testToStringThrowsExceptionForEmptyArray(): void
    {
        $input = [];

        $this->expectException(CastException::class);
        $this->expectExceptionMessage('Value of type array cannot be cast to string');

        Cast::toString($input);
    }

    /**
     * Test that very large integer is converted
     */
    public function testToStringConvertsVeryLargeInteger(): void
    {
        $input  = PHP_INT_MAX;
        $actual = Cast::toString($input);

        self::assertSame((string) PHP_INT_MAX, $actual);
    }

    /**
     * Test that very small integer is converted
     */
    public function testToStringConvertsVerySmallInteger(): void
    {
        $input  = PHP_INT_MIN;
        $actual = Cast::toString($input);

        self::assertSame((string) PHP_INT_MIN, $actual);
    }

    /**
     * Test that unicode string is preserved
     */
    public function testToStringPreservesUnicodeString(): void
    {
        $input  = '你好世界 🌍';
        $actual = Cast::toString($input);

        self::assertSame('你好世界 🌍', $actual);
    }

    /**
     * Test that string with special characters is preserved
     */
    public function testToStringPreservesSpecialCharacters(): void
    {
        $input  = "Hello\nWorld\tTab\r\nNewline";
        $actual = Cast::toString($input);

        self::assertSame("Hello\nWorld\tTab\r\nNewline", $actual);
    }

    /**
     * Test that numeric string is preserved
     */
    public function testToStringPreservesNumericString(): void
    {
        $input  = '123';
        $actual = Cast::toString($input);

        self::assertSame('123', $actual);
    }

    /**
     * Test that float string is preserved
     */
    public function testToStringPreservesFloatString(): void
    {
        $input  = '3.14';
        $actual = Cast::toString($input);

        self::assertSame('3.14', $actual);
    }

    /**
     * Test that INF is converted to string
     */
    public function testToStringConvertsInfinityToString(): void
    {
        $input  = INF;
        $actual = Cast::toString($input);

        self::assertSame('INF', $actual);
    }

    /**
     * Test that negative INF is converted to string
     */
    public function testToStringConvertsNegativeInfinityToString(): void
    {
        $input  = -INF;
        $actual = Cast::toString($input);

        self::assertSame('-INF', $actual);
    }

    /**
     * Test that NAN is converted to string
     */
    public function testToStringConvertsNaNToString(): void
    {
        $input  = NAN;
        $actual = Cast::toString($input);

        self::assertSame('NAN', $actual);
    }
}
