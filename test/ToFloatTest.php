<?php
declare(strict_types=1);

namespace CtwTest\Cast;

use Ctw\Cast\Cast;
use PHPUnit\Framework\TestCase;
use stdClass;

final class ToFloatTest extends TestCase
{
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
     * Test that empty string is converted to 0.0
     */
    public function testToFloatConvertsEmptyStringToZero(): void
    {
        $input  = '';
        $actual = Cast::toFloat($input);

        self::assertSame(0.0, $actual);
    }

    /**
     * Test that whitespace only string is converted to 0.0
     */
    public function testToFloatConvertsWhitespaceOnlyStringToZero(): void
    {
        $input  = '   ';
        $actual = Cast::toFloat($input);

        self::assertSame(0.0, $actual);
    }

    /**
     * Test that non-numeric string is converted to 0.0
     */
    public function testToFloatConvertsNonNumericStringToZero(): void
    {
        $input  = 'hello';
        $actual = Cast::toFloat($input);

        self::assertSame(0.0, $actual);
    }

    /**
     * Test that string with non-numeric characters is converted to 0.0
     */
    public function testToFloatConvertsStringWithNonNumericCharactersToZero(): void
    {
        $input  = '3.14abc';
        $actual = Cast::toFloat($input);

        self::assertSame(0.0, $actual);
    }

    /**
     * Test that array is converted to 0.0
     */
    public function testToFloatConvertsArrayToZero(): void
    {
        $input  = [1.5, 2.5];
        $actual = Cast::toFloat($input);

        self::assertSame(0.0, $actual);
    }

    /**
     * Test that object is converted to 0.0
     */
    public function testToFloatConvertsObjectToZero(): void
    {
        $input  = new stdClass();
        $actual = Cast::toFloat($input);

        self::assertSame(0.0, $actual);
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
     * Test that hex-like string is converted to 0.0
     */
    public function testToFloatConvertsHexLikeStringToZero(): void
    {
        $input  = '0xFF.5';
        $actual = Cast::toFloat($input);

        self::assertSame(0.0, $actual);
    }

    /**
     * Test that word "Infinity" string is converted to 0.0 since it is not numeric.
     */
    public function testToFloatConvertsInfinityWordStringToZero(): void
    {
        $input  = 'Infinity';
        $actual = Cast::toFloat($input);

        self::assertSame(0.0, $actual);
    }

    /**
     * Test that word "NaN" string is converted to 0.0 since it is not numeric.
     */
    public function testToFloatConvertsNanWordStringToZero(): void
    {
        $input  = 'NaN';
        $actual = Cast::toFloat($input);

        self::assertSame(0.0, $actual);
    }

    /**
     * Test that closed resource is converted to 0.0.
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
