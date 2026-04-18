<?php
declare(strict_types=1);

namespace CtwTest\Cast;

use Ctw\Cast\Cast;
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
     * Test that empty string is converted to 0
     */
    public function testToIntConvertsEmptyStringToZero(): void
    {
        $input  = '';
        $actual = Cast::toInt($input);

        self::assertSame(0, $actual);
    }

    /**
     * Test that whitespace only string is converted to 0
     */
    public function testToIntConvertsWhitespaceOnlyStringToZero(): void
    {
        $input  = '   ';
        $actual = Cast::toInt($input);

        self::assertSame(0, $actual);
    }

    /**
     * Test that non-numeric string is converted to 0
     */
    public function testToIntConvertsNonNumericStringToZero(): void
    {
        $input  = 'hello';
        $actual = Cast::toInt($input);

        self::assertSame(0, $actual);
    }

    /**
     * Test that string with non-numeric characters is converted to 0
     */
    public function testToIntConvertsStringWithNonNumericCharactersToZero(): void
    {
        $input  = '42abc';
        $actual = Cast::toInt($input);

        self::assertSame(0, $actual);
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
     * Test that float exceeding PHP_INT_MAX is converted to 0
     */
    public function testToIntConvertsFloatExceedingMaxIntToZero(): void
    {
        $input  = (float) PHP_INT_MAX + 1e10;
        $actual = Cast::toInt($input);

        self::assertSame(0, $actual);
    }

    /**
     * Test that float below PHP_INT_MIN is converted to 0
     */
    public function testToIntConvertsFloatBelowMinIntToZero(): void
    {
        $input  = (float) PHP_INT_MIN - 1e10;
        $actual = Cast::toInt($input);

        self::assertSame(0, $actual);
    }

    /**
     * Test that infinite float is converted to 0
     */
    public function testToIntConvertsInfiniteFloatToZero(): void
    {
        $input  = INF;
        $actual = Cast::toInt($input);

        self::assertSame(0, $actual);
    }

    /**
     * Test that negative infinite float is converted to 0
     */
    public function testToIntConvertsNegativeInfiniteFloatToZero(): void
    {
        $input  = -INF;
        $actual = Cast::toInt($input);

        self::assertSame(0, $actual);
    }

    /**
     * Test that NaN is converted to 0
     */
    public function testToIntConvertsNaNToZero(): void
    {
        $input  = NAN;
        $actual = Cast::toInt($input);

        self::assertSame(0, $actual);
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
     * Test that array is converted to 0
     */
    public function testToIntConvertsArrayToZero(): void
    {
        $input  = [1, 2, 3];
        $actual = Cast::toInt($input);

        self::assertSame(0, $actual);
    }

    /**
     * Test that object is converted to 0
     */
    public function testToIntConvertsObjectToZero(): void
    {
        $input  = new stdClass();
        $actual = Cast::toInt($input);

        self::assertSame(0, $actual);
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
     * Test that hex-like string is converted to 0
     */
    public function testToIntConvertsHexLikeStringToZero(): void
    {
        $input  = '0xFF';
        $actual = Cast::toInt($input);

        self::assertSame(0, $actual);
    }

    /**
     * Test that numeric string exceeding integer range is converted to 0
     */
    public function testToIntConvertsNumericStringExceedingRangeToZero(): void
    {
        $input  = '9999999999999999999999999999';
        $actual = Cast::toInt($input);

        self::assertSame(0, $actual);
    }

    /**
     * Test that numeric string below integer range is converted to 0.
     */
    public function testToIntConvertsNumericStringBelowRangeToZero(): void
    {
        $input  = '-9999999999999999999999999999';
        $actual = Cast::toInt($input);

        self::assertSame(0, $actual);
    }

    /**
     * Test that string "-0" is converted to 0.
     */
    public function testToIntConvertsNegativeZeroStringToZero(): void
    {
        $input  = '-0';
        $actual = Cast::toInt($input);

        self::assertSame(0, $actual);
    }

    /**
     * Test that closed resource is converted to 0.
     */
    public function testToIntConvertsClosedResourceToZero(): void
    {
        $resource = fopen('php://memory', 'r');
        self::assertIsResource($resource);
        fclose($resource);

        $actual = Cast::toInt($resource);

        self::assertSame(0, $actual);
    }
}
