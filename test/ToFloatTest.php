<?php
declare(strict_types=1);

namespace CtwTest\Cast;

use Ctw\Cast\Cast;
use Ctw\Cast\Exception\CastException;
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
}
