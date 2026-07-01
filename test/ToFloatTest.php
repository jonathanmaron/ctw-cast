<?php
declare(strict_types=1);

namespace CtwTest\Cast;

use Ctw\Cast\Cast;
use PHPUnit\Framework\TestCase;
use stdClass;

final class ToFloatTest extends TestCase
{
    /**
     * Test that toFloat returns the float unchanged when given a positive float.
     */
    public function testToFloatReturnsFloatValueUnchanged(): void
    {
        $input  = 3.14;
        $actual = Cast::toFloat($input);

        self::assertSame(3.14, $actual);
    }

    /**
     * Test that toFloat returns 0.0 unchanged when given the float zero.
     */
    public function testToFloatReturnsZeroFloatUnchanged(): void
    {
        $input  = 0.0;
        $actual = Cast::toFloat($input);

        self::assertSame(0.0, $actual);
    }

    /**
     * Test that toFloat returns the float unchanged when given a negative float.
     */
    public function testToFloatReturnsNegativeFloatUnchanged(): void
    {
        $input  = -3.14;
        $actual = Cast::toFloat($input);

        self::assertSame(-3.14, $actual);
    }

    /**
     * Test that toFloat returns the widened float when given a positive integer.
     */
    public function testToFloatConvertsIntegerToFloat(): void
    {
        $input  = 42;
        $actual = Cast::toFloat($input);

        self::assertSame(42.0, $actual);
    }

    /**
     * Test that toFloat returns 0.0 when given the integer zero.
     */
    public function testToFloatConvertsZeroIntegerToFloat(): void
    {
        $input  = 0;
        $actual = Cast::toFloat($input);

        self::assertSame(0.0, $actual);
    }

    /**
     * Test that toFloat returns the widened float when given a negative integer.
     */
    public function testToFloatConvertsNegativeIntegerToFloat(): void
    {
        $input  = -42;
        $actual = Cast::toFloat($input);

        self::assertSame(-42.0, $actual);
    }

    /**
     * Test that toFloat returns 1.0 when given the boolean true.
     */
    public function testToFloatConvertsTrueBooleanToOnePointZero(): void
    {
        $input  = true;
        $actual = Cast::toFloat($input);

        self::assertSame(1.0, $actual);
    }

    /**
     * Test that toFloat returns 0.0 when given the boolean false.
     */
    public function testToFloatConvertsFalseBooleanToZeroPointZero(): void
    {
        $input  = false;
        $actual = Cast::toFloat($input);

        self::assertSame(0.0, $actual);
    }

    /**
     * Test that toFloat returns 0.0 when given null.
     */
    public function testToFloatConvertsNullToZeroPointZero(): void
    {
        $input  = null;
        $actual = Cast::toFloat($input);

        self::assertSame(0.0, $actual);
    }

    /**
     * Test that toFloat returns the parsed float when given a numeric decimal string.
     */
    public function testToFloatConvertsNumericStringToFloat(): void
    {
        $input  = '3.14';
        $actual = Cast::toFloat($input);

        self::assertSame(3.14, $actual);
    }

    /**
     * Test that toFloat returns the widened float when given an integer string.
     */
    public function testToFloatConvertsIntegerStringToFloat(): void
    {
        $input  = '42';
        $actual = Cast::toFloat($input);

        self::assertSame(42.0, $actual);
    }

    /**
     * Test that toFloat returns the parsed negative float when given a negative numeric string.
     */
    public function testToFloatConvertsNegativeNumericString(): void
    {
        $input  = '-3.14';
        $actual = Cast::toFloat($input);

        self::assertSame(-3.14, $actual);
    }

    /**
     * Test that toFloat returns the parsed float when given a numeric string padded with whitespace.
     */
    public function testToFloatConvertsNumericStringWithWhitespace(): void
    {
        $input  = '  3.14  ';
        $actual = Cast::toFloat($input);

        self::assertSame(3.14, $actual);
    }

    /**
     * Test that toFloat returns the parsed value when given a scientific notation string.
     */
    public function testToFloatConvertsScientificNotationString(): void
    {
        $input  = '1.23e-4';
        $actual = Cast::toFloat($input);

        self::assertEqualsWithDelta(0.000123, $actual, 0.0000001);
    }

    /**
     * Test that toFloat returns the parsed value when given a negative scientific notation string.
     */
    public function testToFloatConvertsNegativeScientificNotationString(): void
    {
        $input  = '-1.23e2';
        $actual = Cast::toFloat($input);

        self::assertSame(-123.0, $actual);
    }

    /**
     * Test that toFloat returns 0.0 when given an empty string.
     */
    public function testToFloatConvertsEmptyStringToZero(): void
    {
        $input  = '';
        $actual = Cast::toFloat($input);

        self::assertSame(0.0, $actual);
    }

    /**
     * Test that toFloat returns 0.0 when given a string containing only whitespace.
     */
    public function testToFloatConvertsWhitespaceOnlyStringToZero(): void
    {
        $input  = '   ';
        $actual = Cast::toFloat($input);

        self::assertSame(0.0, $actual);
    }

    /**
     * Test that toFloat returns 0.0 when given a non-numeric string.
     */
    public function testToFloatConvertsNonNumericStringToZero(): void
    {
        $input  = 'hello';
        $actual = Cast::toFloat($input);

        self::assertSame(0.0, $actual);
    }

    /**
     * Test that toFloat returns 0.0 when given a string mixing digits and non-numeric characters.
     */
    public function testToFloatConvertsStringWithNonNumericCharactersToZero(): void
    {
        $input  = '3.14abc';
        $actual = Cast::toFloat($input);

        self::assertSame(0.0, $actual);
    }

    /**
     * Test that toFloat returns 0.0 when given an array.
     */
    public function testToFloatConvertsArrayToZero(): void
    {
        $input  = [1.5, 2.5];
        $actual = Cast::toFloat($input);

        self::assertSame(0.0, $actual);
    }

    /**
     * Test that toFloat returns 0.0 when given an object.
     */
    public function testToFloatConvertsObjectToZero(): void
    {
        $input  = new stdClass();
        $actual = Cast::toFloat($input);

        self::assertSame(0.0, $actual);
    }

    /**
     * Test that toFloat returns 0.0 when given the string "0".
     */
    public function testToFloatConvertsStringZeroToFloat(): void
    {
        $input  = '0';
        $actual = Cast::toFloat($input);

        self::assertSame(0.0, $actual);
    }

    /**
     * Test that toFloat returns the parsed float when given a numeric string with leading zeros.
     */
    public function testToFloatConvertsStringWithLeadingZeros(): void
    {
        $input  = '00042.5';
        $actual = Cast::toFloat($input);

        self::assertSame(42.5, $actual);
    }

    /**
     * Test that toFloat returns the parsed float when given a numeric string with a leading plus sign.
     */
    public function testToFloatConvertsStringWithPlusSign(): void
    {
        $input  = '+3.14';
        $actual = Cast::toFloat($input);

        self::assertSame(3.14, $actual);
    }

    /**
     * Test that toFloat preserves precision when given a very small float near the denormal boundary.
     */
    public function testToFloatPreservesVerySmallFloat(): void
    {
        $input  = 1e-308;
        $actual = Cast::toFloat($input);

        self::assertSame(1e-308, $actual);
    }

    /**
     * Test that toFloat preserves precision when given a very large float near the overflow boundary.
     */
    public function testToFloatPreservesVeryLargeFloat(): void
    {
        $input  = 1e308;
        $actual = Cast::toFloat($input);

        self::assertSame(1e308, $actual);
    }

    /**
     * Test that toFloat passes the value through unchanged when given positive infinity.
     */
    public function testToFloatPreservesInfiniteFloat(): void
    {
        $input  = INF;
        $actual = Cast::toFloat($input);

        self::assertInfinite($actual);
        self::assertGreaterThan(0, $actual);
    }

    /**
     * Test that toFloat passes the value through unchanged when given negative infinity.
     */
    public function testToFloatPreservesNegativeInfiniteFloat(): void
    {
        $input  = -INF;
        $actual = Cast::toFloat($input);

        self::assertInfinite($actual);
        self::assertLessThan(0, $actual);
    }

    /**
     * Test that toFloat passes the value through unchanged when given a NaN float.
     */
    public function testToFloatPreservesNaN(): void
    {
        $input  = NAN;
        $actual = Cast::toFloat($input);

        self::assertNan($actual);
    }

    /**
     * Test that toFloat returns the widened float when given PHP_INT_MAX.
     */
    public function testToFloatConvertsMaxIntegerToFloat(): void
    {
        $input  = PHP_INT_MAX;
        $actual = Cast::toFloat($input);

        self::assertSame((float) PHP_INT_MAX, $actual);
    }

    /**
     * Test that toFloat returns the widened float when given PHP_INT_MIN.
     */
    public function testToFloatConvertsMinIntegerToFloat(): void
    {
        $input  = PHP_INT_MIN;
        $actual = Cast::toFloat($input);

        self::assertSame((float) PHP_INT_MIN, $actual);
    }

    /**
     * Test that toFloat returns the parsed float when given a string starting with a decimal point.
     */
    public function testToFloatConvertsStringWithDecimalPointOnly(): void
    {
        $input  = '.5';
        $actual = Cast::toFloat($input);

        self::assertSame(0.5, $actual);
    }

    /**
     * Test that toFloat returns the parsed float when given a string with a trailing decimal point.
     */
    public function testToFloatConvertsStringWithTrailingDecimalPoint(): void
    {
        $input  = '42.';
        $actual = Cast::toFloat($input);

        self::assertSame(42.0, $actual);
    }

    /**
     * Test that toFloat interprets the value as decimal when given an octal-looking numeric string.
     */
    public function testToFloatTreatsOctalLikeStringAsDecimal(): void
    {
        $input  = '0777.5';
        $actual = Cast::toFloat($input);

        self::assertSame(777.5, $actual);
    }

    /**
     * Test that toFloat returns 0.0 when given a hexadecimal-looking string, since it is not numeric.
     */
    public function testToFloatConvertsHexLikeStringToZero(): void
    {
        $input  = '0xFF.5';
        $actual = Cast::toFloat($input);

        self::assertSame(0.0, $actual);
    }

    /**
     * Test that toFloat returns 0.0 when given the word "Infinity" as a string, since it is not numeric.
     */
    public function testToFloatConvertsInfinityWordStringToZero(): void
    {
        $input  = 'Infinity';
        $actual = Cast::toFloat($input);

        self::assertSame(0.0, $actual);
    }

    /**
     * Test that toFloat returns 0.0 when given the word "NaN" as a string, since it is not numeric.
     */
    public function testToFloatConvertsNanWordStringToZero(): void
    {
        $input  = 'NaN';
        $actual = Cast::toFloat($input);

        self::assertSame(0.0, $actual);
    }

    /**
     * Test that toFloat returns 0.0 when given a closed resource.
     */
    public function testToFloatConvertsClosedResourceToZero(): void
    {
        $resource = fopen('php://memory', 'r');
        self::assertIsResource($resource);
        fclose($resource);

        $actual = Cast::toFloat($resource);

        self::assertSame(0.0, $actual);
    }
}
