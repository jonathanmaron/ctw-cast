<?php
declare(strict_types=1);

namespace CtwTest\Cast;

use ArrayObject;
use Ctw\Cast\Cast;
use Ctw\Cast\Exception\CastException;
use Generator;
use PHPUnit\Framework\TestCase;
use stdClass;
use Stringable;
use Traversable;

final class CastTest extends TestCase
{
    // ========================================
    // Cast::toString() Tests
    // ========================================

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
        $this->expectExceptionMessage('cannot be converted to string');

        Cast::toString($object);
    }

    /**
     * Test that array throws exception
     */
    public function testToStringThrowsExceptionForArray(): void
    {
        $input = ['hello', 'world'];

        $this->expectException(CastException::class);
        $this->expectExceptionMessage('Value of type array cannot be converted to string');

        Cast::toString($input);
    }

    /**
     * Test that empty array throws exception
     */
    public function testToStringThrowsExceptionForEmptyArray(): void
    {
        $input = [];

        $this->expectException(CastException::class);
        $this->expectExceptionMessage('Value of type array cannot be converted to string');

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

    // ========================================
    // Cast::toInt() Tests
    // ========================================

    /**
     * Test that integer value is returned unchanged
     */
    public function testToIntReturnsIntegerValueUnchanged(): void
    {
        $input  = 42;
        $actual = Cast::toInt($input);

        self::assertSame(42, $actual);
    }

    /**
     * Test that zero is returned unchanged
     */
    public function testToIntReturnsZeroUnchanged(): void
    {
        $input  = 0;
        $actual = Cast::toInt($input);

        self::assertSame(0, $actual);
    }

    /**
     * Test that negative integer is returned unchanged
     */
    public function testToIntReturnsNegativeIntegerUnchanged(): void
    {
        $input  = -42;
        $actual = Cast::toInt($input);

        self::assertSame(-42, $actual);
    }

    /**
     * Test that true boolean is converted to one
     */
    public function testToIntConvertsTrueBooleanToOne(): void
    {
        $input  = true;
        $actual = Cast::toInt($input);

        self::assertSame(1, $actual);
    }

    /**
     * Test that false boolean is converted to zero
     */
    public function testToIntConvertsFalseBooleanToZero(): void
    {
        $input  = false;
        $actual = Cast::toInt($input);

        self::assertSame(0, $actual);
    }

    /**
     * Test that numeric string is converted to integer
     */
    public function testToIntConvertsNumericStringToInteger(): void
    {
        $input  = '42';
        $actual = Cast::toInt($input);

        self::assertSame(42, $actual);
    }

    /**
     * Test that negative numeric string is converted
     */
    public function testToIntConvertsNegativeNumericString(): void
    {
        $input  = '-42';
        $actual = Cast::toInt($input);

        self::assertSame(-42, $actual);
    }

    /**
     * Test that numeric string with whitespace is converted
     */
    public function testToIntConvertsNumericStringWithWhitespace(): void
    {
        $input  = '  42  ';
        $actual = Cast::toInt($input);

        self::assertSame(42, $actual);
    }

    /**
     * Test that float string is rounded to integer
     */
    public function testToIntRoundsFloatStringToInteger(): void
    {
        $input  = '3.14';
        $actual = Cast::toInt($input);

        self::assertSame(3, $actual);
    }

    /**
     * Test that float string is rounded up at 0.5
     */
    public function testToIntRoundsFloatStringUp(): void
    {
        $input  = '3.5';
        $actual = Cast::toInt($input);

        self::assertSame(4, $actual);
    }

    /**
     * Test that float string is rounded down below 0.5
     */
    public function testToIntRoundsFloatStringDown(): void
    {
        $input  = '3.4';
        $actual = Cast::toInt($input);

        self::assertSame(3, $actual);
    }

    /**
     * Test that negative float string is rounded correctly
     */
    public function testToIntRoundsNegativeFloatString(): void
    {
        $input  = '-3.5';
        $actual = Cast::toInt($input);

        self::assertSame(-4, $actual);
    }

    /**
     * Test that empty string throws exception
     */
    public function testToIntThrowsExceptionForEmptyString(): void
    {
        $input = '';

        $this->expectException(CastException::class);
        $this->expectExceptionMessage('Empty string cannot be converted to int');

        Cast::toInt($input);
    }

    /**
     * Test that whitespace only string throws exception
     */
    public function testToIntThrowsExceptionForWhitespaceOnlyString(): void
    {
        $input = '   ';

        $this->expectException(CastException::class);
        $this->expectExceptionMessage('Empty string cannot be converted to int');

        Cast::toInt($input);
    }

    /**
     * Test that non-numeric string throws exception
     */
    public function testToIntThrowsExceptionForNonNumericString(): void
    {
        $input = 'hello';

        $this->expectException(CastException::class);
        $this->expectExceptionMessage('is not numeric and cannot be converted to int');

        Cast::toInt($input);
    }

    /**
     * Test that string with non-numeric characters throws exception
     */
    public function testToIntThrowsExceptionForStringWithNonNumericCharacters(): void
    {
        $input = '42abc';

        $this->expectException(CastException::class);
        $this->expectExceptionMessage('is not numeric and cannot be converted to int');

        Cast::toInt($input);
    }

    /**
     * Test that positive float is rounded
     */
    public function testToIntRoundsPositiveFloat(): void
    {
        $input  = 3.14;
        $actual = Cast::toInt($input);

        self::assertSame(3, $actual);
    }

    /**
     * Test that negative float is rounded
     */
    public function testToIntRoundsNegativeFloat(): void
    {
        $input  = -3.14;
        $actual = Cast::toInt($input);

        self::assertSame(-3, $actual);
    }

    /**
     * Test that float is rounded up at 0.5
     */
    public function testToIntRoundsFloatUpAtHalf(): void
    {
        $input  = 2.5;
        $actual = Cast::toInt($input);

        self::assertSame(3, $actual);
    }

    /**
     * Test that negative float is rounded down at 0.5
     */
    public function testToIntRoundsNegativeFloatDownAtHalf(): void
    {
        $input  = -2.5;
        $actual = Cast::toInt($input);

        self::assertSame(-3, $actual);
    }

    /**
     * Test that float exceeding PHP_INT_MAX throws exception
     */
    public function testToIntThrowsExceptionForFloatExceedingMaxInt(): void
    {
        $input = (float) PHP_INT_MAX + 1e10;

        $this->expectException(CastException::class);
        $this->expectExceptionMessage('is out of integer range');

        Cast::toInt($input);
    }

    /**
     * Test that float below PHP_INT_MIN throws exception
     */
    public function testToIntThrowsExceptionForFloatBelowMinInt(): void
    {
        $input = (float) PHP_INT_MIN - 1e10;

        $this->expectException(CastException::class);
        $this->expectExceptionMessage('is out of integer range');

        Cast::toInt($input);
    }

    /**
     * Test that infinite float throws exception
     */
    public function testToIntThrowsExceptionForInfiniteFloat(): void
    {
        $input = INF;

        $this->expectException(CastException::class);
        $this->expectExceptionMessage('infinite or NaN');

        Cast::toInt($input);
    }

    /**
     * Test that negative infinite float throws exception
     */
    public function testToIntThrowsExceptionForNegativeInfiniteFloat(): void
    {
        $input = -INF;

        $this->expectException(CastException::class);
        $this->expectExceptionMessage('infinite or NaN');

        Cast::toInt($input);
    }

    /**
     * Test that NaN throws exception
     */
    public function testToIntThrowsExceptionForNaN(): void
    {
        $input = NAN;

        $this->expectException(CastException::class);
        $this->expectExceptionMessage('infinite or NaN');

        Cast::toInt($input);
    }

    /**
     * Test that null is converted to zero
     */
    public function testToIntConvertsNullToZero(): void
    {
        $input  = null;
        $actual = Cast::toInt($input);

        self::assertSame(0, $actual);
    }

    /**
     * Test that array throws exception
     */
    public function testToIntThrowsExceptionForArray(): void
    {
        $input = [1, 2, 3];

        $this->expectException(CastException::class);
        $this->expectExceptionMessage('cannot be converted to int');

        Cast::toInt($input);
    }

    /**
     * Test that object throws exception
     */
    public function testToIntThrowsExceptionForObject(): void
    {
        $input = new stdClass();

        $this->expectException(CastException::class);
        $this->expectExceptionMessage('cannot be converted to int');

        Cast::toInt($input);
    }

    /**
     * Test that string zero is converted
     */
    public function testToIntConvertsStringZero(): void
    {
        $input  = '0';
        $actual = Cast::toInt($input);

        self::assertSame(0, $actual);
    }

    /**
     * Test that PHP_INT_MAX is returned unchanged
     */
    public function testToIntReturnsMaxIntegerUnchanged(): void
    {
        $input  = PHP_INT_MAX;
        $actual = Cast::toInt($input);

        self::assertSame(PHP_INT_MAX, $actual);
    }

    /**
     * Test that PHP_INT_MIN is returned unchanged
     */
    public function testToIntReturnsMinIntegerUnchanged(): void
    {
        $input  = PHP_INT_MIN;
        $actual = Cast::toInt($input);

        self::assertSame(PHP_INT_MIN, $actual);
    }

    /**
     * Test that scientific notation string is converted
     */
    public function testToIntConvertsScientificNotationString(): void
    {
        $input  = '1e3';
        $actual = Cast::toInt($input);

        self::assertSame(1000, $actual);
    }

    /**
     * Test that negative scientific notation string is converted
     */
    public function testToIntConvertsNegativeScientificNotationString(): void
    {
        $input  = '-1e3';
        $actual = Cast::toInt($input);

        self::assertSame(-1000, $actual);
    }

    /**
     * Test that decimal scientific notation string is rounded
     */
    public function testToIntRoundsDecimalScientificNotationString(): void
    {
        $input  = '1.5e2';
        $actual = Cast::toInt($input);

        self::assertSame(150, $actual);
    }

    /**
     * Test that string with leading zeros is converted
     */
    public function testToIntConvertsStringWithLeadingZeros(): void
    {
        $input  = '00042';
        $actual = Cast::toInt($input);

        self::assertSame(42, $actual);
    }

    /**
     * Test that string with plus sign is converted
     */
    public function testToIntConvertsStringWithPlusSign(): void
    {
        $input  = '+42';
        $actual = Cast::toInt($input);

        self::assertSame(42, $actual);
    }

    /**
     * Test that octal-like string is treated as decimal
     */
    public function testToIntTreatsOctalLikeStringAsDecimal(): void
    {
        $input  = '0777';
        $actual = Cast::toInt($input);

        self::assertSame(777, $actual);
    }

    /**
     * Test that hex-like string throws exception
     */
    public function testToIntThrowsExceptionForHexLikeString(): void
    {
        $input = '0xFF';

        $this->expectException(CastException::class);
        $this->expectExceptionMessage('is not numeric and cannot be converted to int');

        Cast::toInt($input);
    }

    /**
     * Test that numeric string exceeding integer range throws exception
     */
    public function testToIntThrowsExceptionForNumericStringExceedingRange(): void
    {
        $input = '9999999999999999999999999999';

        $this->expectException(CastException::class);
        $this->expectExceptionMessage('is out of integer range');

        Cast::toInt($input);
    }

    // ========================================
    // Cast::toFloat() Tests
    // ========================================

    /**
     * Test that float value is returned unchanged
     */
    public function testToFloatReturnsFloatValueUnchanged(): void
    {
        $input  = 3.14;
        $actual = Cast::toFloat($input);

        self::assertSame(3.14, $actual);
    }

    /**
     * Test that zero float is returned unchanged
     */
    public function testToFloatReturnsZeroFloatUnchanged(): void
    {
        $input  = 0.0;
        $actual = Cast::toFloat($input);

        self::assertSame(0.0, $actual);
    }

    /**
     * Test that negative float is returned unchanged
     */
    public function testToFloatReturnsNegativeFloatUnchanged(): void
    {
        $input  = -3.14;
        $actual = Cast::toFloat($input);

        self::assertSame(-3.14, $actual);
    }

    /**
     * Test that integer is converted to float
     */
    public function testToFloatConvertsIntegerToFloat(): void
    {
        $input  = 42;
        $actual = Cast::toFloat($input);

        self::assertSame(42.0, $actual);
    }

    /**
     * Test that zero integer is converted to float
     */
    public function testToFloatConvertsZeroIntegerToFloat(): void
    {
        $input  = 0;
        $actual = Cast::toFloat($input);

        self::assertSame(0.0, $actual);
    }

    /**
     * Test that negative integer is converted to float
     */
    public function testToFloatConvertsNegativeIntegerToFloat(): void
    {
        $input  = -42;
        $actual = Cast::toFloat($input);

        self::assertSame(-42.0, $actual);
    }

    /**
     * Test that true boolean is converted to 1.0
     */
    public function testToFloatConvertsTrueBooleanToOnePointZero(): void
    {
        $input  = true;
        $actual = Cast::toFloat($input);

        self::assertSame(1.0, $actual);
    }

    /**
     * Test that false boolean is converted to 0.0
     */
    public function testToFloatConvertsFalseBooleanToZeroPointZero(): void
    {
        $input  = false;
        $actual = Cast::toFloat($input);

        self::assertSame(0.0, $actual);
    }

    /**
     * Test that null is converted to 0.0
     */
    public function testToFloatConvertsNullToZeroPointZero(): void
    {
        $input  = null;
        $actual = Cast::toFloat($input);

        self::assertSame(0.0, $actual);
    }

    /**
     * Test that numeric string is converted to float
     */
    public function testToFloatConvertsNumericStringToFloat(): void
    {
        $input  = '3.14';
        $actual = Cast::toFloat($input);

        self::assertSame(3.14, $actual);
    }

    /**
     * Test that integer string is converted to float
     */
    public function testToFloatConvertsIntegerStringToFloat(): void
    {
        $input  = '42';
        $actual = Cast::toFloat($input);

        self::assertSame(42.0, $actual);
    }

    /**
     * Test that negative numeric string is converted
     */
    public function testToFloatConvertsNegativeNumericString(): void
    {
        $input  = '-3.14';
        $actual = Cast::toFloat($input);

        self::assertSame(-3.14, $actual);
    }

    /**
     * Test that numeric string with whitespace is converted
     */
    public function testToFloatConvertsNumericStringWithWhitespace(): void
    {
        $input  = '  3.14  ';
        $actual = Cast::toFloat($input);

        self::assertSame(3.14, $actual);
    }

    /**
     * Test that scientific notation string is converted
     */
    public function testToFloatConvertsScientificNotationString(): void
    {
        $input  = '1.23e-4';
        $actual = Cast::toFloat($input);

        self::assertEqualsWithDelta(0.000123, $actual, 0.0000001);
    }

    /**
     * Test that negative scientific notation string is converted
     */
    public function testToFloatConvertsNegativeScientificNotationString(): void
    {
        $input  = '-1.23e2';
        $actual = Cast::toFloat($input);

        self::assertSame(-123.0, $actual);
    }

    /**
     * Test that empty string throws exception
     */
    public function testToFloatThrowsExceptionForEmptyString(): void
    {
        $input = '';

        $this->expectException(CastException::class);
        $this->expectExceptionMessage('Empty string cannot be converted to float');

        Cast::toFloat($input);
    }

    /**
     * Test that whitespace only string throws exception
     */
    public function testToFloatThrowsExceptionForWhitespaceOnlyString(): void
    {
        $input = '   ';

        $this->expectException(CastException::class);
        $this->expectExceptionMessage('Empty string cannot be converted to float');

        Cast::toFloat($input);
    }

    /**
     * Test that non-numeric string throws exception
     */
    public function testToFloatThrowsExceptionForNonNumericString(): void
    {
        $input = 'hello';

        $this->expectException(CastException::class);
        $this->expectExceptionMessage('is not numeric and cannot be converted to float');

        Cast::toFloat($input);
    }

    /**
     * Test that string with non-numeric characters throws exception
     */
    public function testToFloatThrowsExceptionForStringWithNonNumericCharacters(): void
    {
        $input = '3.14abc';

        $this->expectException(CastException::class);
        $this->expectExceptionMessage('is not numeric and cannot be converted to float');

        Cast::toFloat($input);
    }

    /**
     * Test that array throws exception
     */
    public function testToFloatThrowsExceptionForArray(): void
    {
        $input = [1.5, 2.5];

        $this->expectException(CastException::class);
        $this->expectExceptionMessage('cannot be converted to float');

        Cast::toFloat($input);
    }

    /**
     * Test that object throws exception
     */
    public function testToFloatThrowsExceptionForObject(): void
    {
        $input = new stdClass();

        $this->expectException(CastException::class);
        $this->expectExceptionMessage('cannot be converted to float');

        Cast::toFloat($input);
    }

    /**
     * Test that string zero is converted to float
     */
    public function testToFloatConvertsStringZeroToFloat(): void
    {
        $input  = '0';
        $actual = Cast::toFloat($input);

        self::assertSame(0.0, $actual);
    }

    /**
     * Test that string with leading zeros is converted
     */
    public function testToFloatConvertsStringWithLeadingZeros(): void
    {
        $input  = '00042.5';
        $actual = Cast::toFloat($input);

        self::assertSame(42.5, $actual);
    }

    /**
     * Test that string with plus sign is converted
     */
    public function testToFloatConvertsStringWithPlusSign(): void
    {
        $input  = '+3.14';
        $actual = Cast::toFloat($input);

        self::assertSame(3.14, $actual);
    }

    /**
     * Test that very small float is preserved
     */
    public function testToFloatPreservesVerySmallFloat(): void
    {
        $input  = 1e-308;
        $actual = Cast::toFloat($input);

        self::assertSame(1e-308, $actual);
    }

    /**
     * Test that very large float is preserved
     */
    public function testToFloatPreservesVeryLargeFloat(): void
    {
        $input  = 1e308;
        $actual = Cast::toFloat($input);

        self::assertSame(1e308, $actual);
    }

    /**
     * Test that infinite float is preserved
     */
    public function testToFloatPreservesInfiniteFloat(): void
    {
        $input  = INF;
        $actual = Cast::toFloat($input);

        self::assertInfinite($actual);
        self::assertGreaterThan(0, $actual);
    }

    /**
     * Test that negative infinite float is preserved
     */
    public function testToFloatPreservesNegativeInfiniteFloat(): void
    {
        $input  = -INF;
        $actual = Cast::toFloat($input);

        self::assertInfinite($actual);
        self::assertLessThan(0, $actual);
    }

    /**
     * Test that NaN is preserved
     */
    public function testToFloatPreservesNaN(): void
    {
        $input  = NAN;
        $actual = Cast::toFloat($input);

        self::assertNan($actual);
    }

    /**
     * Test that PHP_INT_MAX is converted to float
     */
    public function testToFloatConvertsMaxIntegerToFloat(): void
    {
        $input  = PHP_INT_MAX;
        $actual = Cast::toFloat($input);

        self::assertSame((float) PHP_INT_MAX, $actual);
    }

    /**
     * Test that PHP_INT_MIN is converted to float
     */
    public function testToFloatConvertsMinIntegerToFloat(): void
    {
        $input  = PHP_INT_MIN;
        $actual = Cast::toFloat($input);

        self::assertSame((float) PHP_INT_MIN, $actual);
    }

    /**
     * Test that string with decimal point only is converted
     */
    public function testToFloatConvertsStringWithDecimalPointOnly(): void
    {
        $input  = '.5';
        $actual = Cast::toFloat($input);

        self::assertSame(0.5, $actual);
    }

    /**
     * Test that string with trailing decimal point is converted
     */
    public function testToFloatConvertsStringWithTrailingDecimalPoint(): void
    {
        $input  = '42.';
        $actual = Cast::toFloat($input);

        self::assertSame(42.0, $actual);
    }

    /**
     * Test that octal-like string is treated as decimal
     */
    public function testToFloatTreatsOctalLikeStringAsDecimal(): void
    {
        $input  = '0777.5';
        $actual = Cast::toFloat($input);

        self::assertSame(777.5, $actual);
    }

    /**
     * Test that hex-like string throws exception
     */
    public function testToFloatThrowsExceptionForHexLikeString(): void
    {
        $input = '0xFF.5';

        $this->expectException(CastException::class);
        $this->expectExceptionMessage('is not numeric and cannot be converted to float');

        Cast::toFloat($input);
    }

    // ========================================
    // Cast::toBool() Tests
    // ========================================

    /**
     * Test that true boolean is returned unchanged
     */
    public function testToBoolReturnsTrueBooleanUnchanged(): void
    {
        $input  = true;
        $actual = Cast::toBool($input);

        self::assertTrue($actual);
    }

    /**
     * Test that false boolean is returned unchanged
     */
    public function testToBoolReturnsFalseBooleanUnchanged(): void
    {
        $input  = false;
        $actual = Cast::toBool($input);

        self::assertFalse($actual);
    }

    /**
     * Test that integer one is converted to true
     */
    public function testToBoolConvertsIntegerOneToTrue(): void
    {
        $input  = 1;
        $actual = Cast::toBool($input);

        self::assertTrue($actual);
    }

    /**
     * Test that integer zero is converted to false
     */
    public function testToBoolConvertsIntegerZeroToFalse(): void
    {
        $input  = 0;
        $actual = Cast::toBool($input);

        self::assertFalse($actual);
    }

    /**
     * Test that integer two throws exception
     */
    public function testToBoolThrowsExceptionForIntegerTwo(): void
    {
        $input = 2;

        $this->expectException(CastException::class);
        $this->expectExceptionMessage('only 0 and 1 are accepted');

        Cast::toBool($input);
    }

    /**
     * Test that negative integer throws exception
     */
    public function testToBoolThrowsExceptionForNegativeInteger(): void
    {
        $input = -1;

        $this->expectException(CastException::class);
        $this->expectExceptionMessage('only 0 and 1 are accepted');

        Cast::toBool($input);
    }

    /**
     * Test that float 1.0 is converted to true
     */
    public function testToBoolConvertsFloatOneToTrue(): void
    {
        $input  = 1.0;
        $actual = Cast::toBool($input);

        self::assertTrue($actual);
    }

    /**
     * Test that float 0.0 is converted to false
     */
    public function testToBoolConvertsFloatZeroToFalse(): void
    {
        $input  = 0.0;
        $actual = Cast::toBool($input);

        self::assertFalse($actual);
    }

    /**
     * Test that float 1.5 throws exception
     */
    public function testToBoolThrowsExceptionForFloatOnePointFive(): void
    {
        $input = 1.5;

        $this->expectException(CastException::class);
        $this->expectExceptionMessage('only 0.0 and 1.0 are accepted');

        Cast::toBool($input);
    }

    /**
     * Test that string "true" is converted to true
     */
    public function testToBoolConvertsStringTrueToTrue(): void
    {
        $input  = 'true';
        $actual = Cast::toBool($input);

        self::assertTrue($actual);
    }

    /**
     * Test that uppercase string "TRUE" is converted to true
     */
    public function testToBoolConvertsUppercaseStringTrueToTrue(): void
    {
        $input  = 'TRUE';
        $actual = Cast::toBool($input);

        self::assertTrue($actual);
    }

    /**
     * Test that mixed case string "TrUe" is converted to true
     */
    public function testToBoolConvertsMixedCaseStringTrueToTrue(): void
    {
        $input  = 'TrUe';
        $actual = Cast::toBool($input);

        self::assertTrue($actual);
    }

    /**
     * Test that string "1" is converted to true
     */
    public function testToBoolConvertsStringOneToTrue(): void
    {
        $input  = '1';
        $actual = Cast::toBool($input);

        self::assertTrue($actual);
    }

    /**
     * Test that string "yes" is converted to true
     */
    public function testToBoolConvertsStringYesToTrue(): void
    {
        $input  = 'yes';
        $actual = Cast::toBool($input);

        self::assertTrue($actual);
    }

    /**
     * Test that string "on" is converted to true
     */
    public function testToBoolConvertsStringOnToTrue(): void
    {
        $input  = 'on';
        $actual = Cast::toBool($input);

        self::assertTrue($actual);
    }

    /**
     * Test that string "y" is converted to true
     */
    public function testToBoolConvertsStringYToTrue(): void
    {
        $input  = 'y';
        $actual = Cast::toBool($input);

        self::assertTrue($actual);
    }

    /**
     * Test that string "t" is converted to true
     */
    public function testToBoolConvertsStringTToTrue(): void
    {
        $input  = 't';
        $actual = Cast::toBool($input);

        self::assertTrue($actual);
    }

    /**
     * Test that string "false" is converted to false
     */
    public function testToBoolConvertsStringFalseToFalse(): void
    {
        $input  = 'false';
        $actual = Cast::toBool($input);

        self::assertFalse($actual);
    }

    /**
     * Test that uppercase string "FALSE" is converted to false
     */
    public function testToBoolConvertsUppercaseStringFalseToFalse(): void
    {
        $input  = 'FALSE';
        $actual = Cast::toBool($input);

        self::assertFalse($actual);
    }

    /**
     * Test that string "0" is converted to false
     */
    public function testToBoolConvertsStringZeroToFalse(): void
    {
        $input  = '0';
        $actual = Cast::toBool($input);

        self::assertFalse($actual);
    }

    /**
     * Test that string "no" is converted to false
     */
    public function testToBoolConvertsStringNoToFalse(): void
    {
        $input  = 'no';
        $actual = Cast::toBool($input);

        self::assertFalse($actual);
    }

    /**
     * Test that string "off" is converted to false
     */
    public function testToBoolConvertsStringOffToFalse(): void
    {
        $input  = 'off';
        $actual = Cast::toBool($input);

        self::assertFalse($actual);
    }

    /**
     * Test that string "n" is converted to false
     */
    public function testToBoolConvertsStringNToFalse(): void
    {
        $input  = 'n';
        $actual = Cast::toBool($input);

        self::assertFalse($actual);
    }

    /**
     * Test that string "f" is converted to false
     */
    public function testToBoolConvertsStringFToFalse(): void
    {
        $input  = 'f';
        $actual = Cast::toBool($input);

        self::assertFalse($actual);
    }

    /**
     * Test that empty string is converted to false
     */
    public function testToBoolConvertsEmptyStringToFalse(): void
    {
        $input  = '';
        $actual = Cast::toBool($input);

        self::assertFalse($actual);
    }

    /**
     * Test that whitespace only string is converted to false
     */
    public function testToBoolConvertsWhitespaceOnlyStringToFalse(): void
    {
        $input  = '   ';
        $actual = Cast::toBool($input);

        self::assertFalse($actual);
    }

    /**
     * Test that whitespace is trimmed from string before conversion
     */
    public function testToBoolTrimsWhitespaceFromStringBeforeConversion(): void
    {
        $input  = '  true  ';
        $actual = Cast::toBool($input);

        self::assertTrue($actual);
    }

    /**
     * Test that invalid string throws exception
     */
    public function testToBoolThrowsExceptionForInvalidString(): void
    {
        $input = 'maybe';

        $this->expectException(CastException::class);
        $this->expectExceptionMessage('cannot be converted to bool');

        Cast::toBool($input);
    }

    /**
     * Test that numeric string other than 0 or 1 throws exception
     */
    public function testToBoolThrowsExceptionForNumericStringOtherThanZeroOrOne(): void
    {
        $input = '2';

        $this->expectException(CastException::class);
        $this->expectExceptionMessage('cannot be converted to bool');

        Cast::toBool($input);
    }

    /**
     * Test that null is converted to false
     */
    public function testToBoolConvertsNullToFalse(): void
    {
        $input  = null;
        $actual = Cast::toBool($input);

        self::assertFalse($actual);
    }

    /**
     * Test that array throws exception
     */
    public function testToBoolThrowsExceptionForArray(): void
    {
        $input = [true, false];

        $this->expectException(CastException::class);
        $this->expectExceptionMessage('cannot be converted to bool');

        Cast::toBool($input);
    }

    /**
     * Test that object throws exception
     */
    public function testToBoolThrowsExceptionForObject(): void
    {
        $input = new stdClass();

        $this->expectException(CastException::class);
        $this->expectExceptionMessage('cannot be converted to bool');

        Cast::toBool($input);
    }

    /**
     * Test that uppercase "YES" is converted to true
     */
    public function testToBoolConvertsUppercaseYesToTrue(): void
    {
        $input  = 'YES';
        $actual = Cast::toBool($input);

        self::assertTrue($actual);
    }

    /**
     * Test that uppercase "NO" is converted to false
     */
    public function testToBoolConvertsUppercaseNoToFalse(): void
    {
        $input  = 'NO';
        $actual = Cast::toBool($input);

        self::assertFalse($actual);
    }

    /**
     * Test that uppercase "ON" is converted to true
     */
    public function testToBoolConvertsUppercaseOnToTrue(): void
    {
        $input  = 'ON';
        $actual = Cast::toBool($input);

        self::assertTrue($actual);
    }

    /**
     * Test that uppercase "OFF" is converted to false
     */
    public function testToBoolConvertsUppercaseOffToFalse(): void
    {
        $input  = 'OFF';
        $actual = Cast::toBool($input);

        self::assertFalse($actual);
    }

    /**
     * Test that uppercase "Y" is converted to true
     */
    public function testToBoolConvertsUppercaseYToTrue(): void
    {
        $input  = 'Y';
        $actual = Cast::toBool($input);

        self::assertTrue($actual);
    }

    /**
     * Test that uppercase "N" is converted to false
     */
    public function testToBoolConvertsUppercaseNToFalse(): void
    {
        $input  = 'N';
        $actual = Cast::toBool($input);

        self::assertFalse($actual);
    }

    /**
     * Test that uppercase "T" is converted to true
     */
    public function testToBoolConvertsUppercaseTToTrue(): void
    {
        $input  = 'T';
        $actual = Cast::toBool($input);

        self::assertTrue($actual);
    }

    /**
     * Test that uppercase "F" is converted to false
     */
    public function testToBoolConvertsUppercaseFToFalse(): void
    {
        $input  = 'F';
        $actual = Cast::toBool($input);

        self::assertFalse($actual);
    }

    /**
     * Test that tab character is converted to false
     */
    public function testToBoolConvertsTabCharacterToFalse(): void
    {
        $input  = "\t";
        $actual = Cast::toBool($input);

        self::assertFalse($actual);
    }

    /**
     * Test that newline character is converted to false
     */
    public function testToBoolConvertsNewlineCharacterToFalse(): void
    {
        $input  = "\n";
        $actual = Cast::toBool($input);

        self::assertFalse($actual);
    }

    /**
     * Test that string with whitespace and invalid value throws exception
     */
    public function testToBoolThrowsExceptionForStringWithWhitespaceAndInvalidValue(): void
    {
        $input = '  invalid  ';

        $this->expectException(CastException::class);
        $this->expectExceptionMessage('cannot be converted to bool');

        Cast::toBool($input);
    }

    /**
     * Test that positive float other than 1.0 throws exception
     */
    public function testToBoolThrowsExceptionForPositiveFloatOtherThanOne(): void
    {
        $input = 2.0;

        $this->expectException(CastException::class);
        $this->expectExceptionMessage('only 0.0 and 1.0 are accepted');

        Cast::toBool($input);
    }

    /**
     * Test that negative float throws exception
     */
    public function testToBoolThrowsExceptionForNegativeFloat(): void
    {
        $input = -1.0;

        $this->expectException(CastException::class);
        $this->expectExceptionMessage('only 0.0 and 1.0 are accepted');

        Cast::toBool($input);
    }

    // ========================================
    // Cast::toArray() Tests
    // ========================================

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
