<?php
declare(strict_types=1);

namespace CtwTest\Cast;

use Ctw\Cast\Cast;
use Ctw\Cast\Exception\CastException;
use PHPUnit\Framework\TestCase;
use stdClass;

final class ToIntTest extends TestCase
{
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
        $this->expectExceptionMessage('Empty string cannot be cast to int');

        Cast::toInt($input);
    }

    /**
     * Test that whitespace only string throws exception
     */
    public function testToIntThrowsExceptionForWhitespaceOnlyString(): void
    {
        $input = '   ';

        $this->expectException(CastException::class);
        $this->expectExceptionMessage('Empty string cannot be cast to int');

        Cast::toInt($input);
    }

    /**
     * Test that non-numeric string throws exception
     */
    public function testToIntThrowsExceptionForNonNumericString(): void
    {
        $input = 'hello';

        $this->expectException(CastException::class);
        $this->expectExceptionMessage('is not numeric and cannot be cast to int');

        Cast::toInt($input);
    }

    /**
     * Test that string with non-numeric characters throws exception
     */
    public function testToIntThrowsExceptionForStringWithNonNumericCharacters(): void
    {
        $input = '42abc';

        $this->expectException(CastException::class);
        $this->expectExceptionMessage('is not numeric and cannot be cast to int');

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
        $this->expectExceptionMessage('cannot be cast to int');

        Cast::toInt($input);
    }

    /**
     * Test that object throws exception
     */
    public function testToIntThrowsExceptionForObject(): void
    {
        $input = new stdClass();

        $this->expectException(CastException::class);
        $this->expectExceptionMessage('cannot be cast to int');

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
        $this->expectExceptionMessage('is not numeric and cannot be cast to int');

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
}
