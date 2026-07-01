<?php
declare(strict_types=1);

namespace CtwTest\Cast;

use Ctw\Cast\Cast;
use PHPUnit\Framework\TestCase;
use stdClass;

final class ToIntTest extends TestCase
{
    /**
     * Test that toInt returns the integer unchanged when given a positive integer.
     */
    public function testToIntReturnsIntegerValueUnchanged(): void
    {
        $input  = 42;
        $actual = Cast::toInt($input);

        self::assertSame(42, $actual);
    }

    /**
     * Test that toInt returns zero unchanged when given the integer zero.
     */
    public function testToIntReturnsZeroUnchanged(): void
    {
        $input  = 0;
        $actual = Cast::toInt($input);

        self::assertSame(0, $actual);
    }

    /**
     * Test that toInt returns the integer unchanged when given a negative integer.
     */
    public function testToIntReturnsNegativeIntegerUnchanged(): void
    {
        $input  = -42;
        $actual = Cast::toInt($input);

        self::assertSame(-42, $actual);
    }

    /**
     * Test that toInt returns 1 when given the boolean true.
     */
    public function testToIntConvertsTrueBooleanToOne(): void
    {
        $input  = true;
        $actual = Cast::toInt($input);

        self::assertSame(1, $actual);
    }

    /**
     * Test that toInt returns 0 when given the boolean false.
     */
    public function testToIntConvertsFalseBooleanToZero(): void
    {
        $input  = false;
        $actual = Cast::toInt($input);

        self::assertSame(0, $actual);
    }

    /**
     * Test that toInt returns the parsed integer when given a numeric string.
     */
    public function testToIntConvertsNumericStringToInteger(): void
    {
        $input  = '42';
        $actual = Cast::toInt($input);

        self::assertSame(42, $actual);
    }

    /**
     * Test that toInt returns the parsed negative integer when given a negative numeric string.
     */
    public function testToIntConvertsNegativeNumericString(): void
    {
        $input  = '-42';
        $actual = Cast::toInt($input);

        self::assertSame(-42, $actual);
    }

    /**
     * Test that toInt returns the parsed integer when given a numeric string padded with whitespace.
     */
    public function testToIntConvertsNumericStringWithWhitespace(): void
    {
        $input  = '  42  ';
        $actual = Cast::toInt($input);

        self::assertSame(42, $actual);
    }

    /**
     * Test that toInt rounds down when given a float string below the half boundary.
     */
    public function testToIntRoundsFloatStringToInteger(): void
    {
        $input  = '3.14';
        $actual = Cast::toInt($input);

        self::assertSame(3, $actual);
    }

    /**
     * Test that toInt rounds up when given a float string exactly at the half boundary.
     */
    public function testToIntRoundsFloatStringUp(): void
    {
        $input  = '3.5';
        $actual = Cast::toInt($input);

        self::assertSame(4, $actual);
    }

    /**
     * Test that toInt rounds down when given a float string just below the half boundary.
     */
    public function testToIntRoundsFloatStringDown(): void
    {
        $input  = '3.4';
        $actual = Cast::toInt($input);

        self::assertSame(3, $actual);
    }

    /**
     * Test that toInt rounds away from zero when given a negative float string at the half boundary.
     */
    public function testToIntRoundsNegativeFloatString(): void
    {
        $input  = '-3.5';
        $actual = Cast::toInt($input);

        self::assertSame(-4, $actual);
    }

    /**
     * Test that toInt returns 0 when given an empty string.
     */
    public function testToIntConvertsEmptyStringToZero(): void
    {
        $input  = '';
        $actual = Cast::toInt($input);

        self::assertSame(0, $actual);
    }

    /**
     * Test that toInt returns 0 when given a string containing only whitespace.
     */
    public function testToIntConvertsWhitespaceOnlyStringToZero(): void
    {
        $input  = '   ';
        $actual = Cast::toInt($input);

        self::assertSame(0, $actual);
    }

    /**
     * Test that toInt returns 0 when given a non-numeric string.
     */
    public function testToIntConvertsNonNumericStringToZero(): void
    {
        $input  = 'hello';
        $actual = Cast::toInt($input);

        self::assertSame(0, $actual);
    }

    /**
     * Test that toInt returns 0 when given a string mixing digits and non-numeric characters.
     */
    public function testToIntConvertsStringWithNonNumericCharactersToZero(): void
    {
        $input  = '42abc';
        $actual = Cast::toInt($input);

        self::assertSame(0, $actual);
    }

    /**
     * Test that toInt rounds down when given a positive float below the half boundary.
     */
    public function testToIntRoundsPositiveFloat(): void
    {
        $input  = 3.14;
        $actual = Cast::toInt($input);

        self::assertSame(3, $actual);
    }

    /**
     * Test that toInt rounds toward zero when given a negative float below the half boundary.
     */
    public function testToIntRoundsNegativeFloat(): void
    {
        $input  = -3.14;
        $actual = Cast::toInt($input);

        self::assertSame(-3, $actual);
    }

    /**
     * Test that toInt rounds up when given a positive float exactly at the half boundary.
     */
    public function testToIntRoundsFloatUpAtHalf(): void
    {
        $input  = 2.5;
        $actual = Cast::toInt($input);

        self::assertSame(3, $actual);
    }

    /**
     * Test that toInt rounds away from zero when given a negative float exactly at the half boundary.
     */
    public function testToIntRoundsNegativeFloatDownAtHalf(): void
    {
        $input  = -2.5;
        $actual = Cast::toInt($input);

        self::assertSame(-3, $actual);
    }

    /**
     * Test that toInt returns 0 when given a float that exceeds PHP_INT_MAX.
     */
    public function testToIntConvertsFloatExceedingMaxIntToZero(): void
    {
        $input  = (float) PHP_INT_MAX + 1e10;
        $actual = Cast::toInt($input);

        self::assertSame(0, $actual);
    }

    /**
     * Test that toInt returns 0 when given a float that falls below PHP_INT_MIN.
     */
    public function testToIntConvertsFloatBelowMinIntToZero(): void
    {
        $input  = (float) PHP_INT_MIN - 1e10;
        $actual = Cast::toInt($input);

        self::assertSame(0, $actual);
    }

    /**
     * Test that toInt returns 0 when given positive infinity.
     */
    public function testToIntConvertsInfiniteFloatToZero(): void
    {
        $input  = INF;
        $actual = Cast::toInt($input);

        self::assertSame(0, $actual);
    }

    /**
     * Test that toInt returns 0 when given negative infinity.
     */
    public function testToIntConvertsNegativeInfiniteFloatToZero(): void
    {
        $input  = -INF;
        $actual = Cast::toInt($input);

        self::assertSame(0, $actual);
    }

    /**
     * Test that toInt returns 0 when given a NaN float.
     */
    public function testToIntConvertsNaNToZero(): void
    {
        $input  = NAN;
        $actual = Cast::toInt($input);

        self::assertSame(0, $actual);
    }

    /**
     * Test that toInt returns 0 when given null.
     */
    public function testToIntConvertsNullToZero(): void
    {
        $input  = null;
        $actual = Cast::toInt($input);

        self::assertSame(0, $actual);
    }

    /**
     * Test that toInt returns 0 when given an array.
     */
    public function testToIntConvertsArrayToZero(): void
    {
        $input  = [1, 2, 3];
        $actual = Cast::toInt($input);

        self::assertSame(0, $actual);
    }

    /**
     * Test that toInt returns 0 when given an object.
     */
    public function testToIntConvertsObjectToZero(): void
    {
        $input  = new stdClass();
        $actual = Cast::toInt($input);

        self::assertSame(0, $actual);
    }

    /**
     * Test that toInt returns 0 when given the string "0".
     */
    public function testToIntConvertsStringZero(): void
    {
        $input  = '0';
        $actual = Cast::toInt($input);

        self::assertSame(0, $actual);
    }

    /**
     * Test that toInt returns PHP_INT_MAX unchanged when given the maximum integer.
     */
    public function testToIntReturnsMaxIntegerUnchanged(): void
    {
        $input  = PHP_INT_MAX;
        $actual = Cast::toInt($input);

        self::assertSame(PHP_INT_MAX, $actual);
    }

    /**
     * Test that toInt returns PHP_INT_MIN unchanged when given the minimum integer.
     */
    public function testToIntReturnsMinIntegerUnchanged(): void
    {
        $input  = PHP_INT_MIN;
        $actual = Cast::toInt($input);

        self::assertSame(PHP_INT_MIN, $actual);
    }

    /**
     * Test that toInt returns the expanded integer when given a scientific notation string.
     */
    public function testToIntConvertsScientificNotationString(): void
    {
        $input  = '1e3';
        $actual = Cast::toInt($input);

        self::assertSame(1000, $actual);
    }

    /**
     * Test that toInt returns the expanded negative integer when given a negative scientific notation string.
     */
    public function testToIntConvertsNegativeScientificNotationString(): void
    {
        $input  = '-1e3';
        $actual = Cast::toInt($input);

        self::assertSame(-1000, $actual);
    }

    /**
     * Test that toInt returns the rounded integer when given a decimal scientific notation string.
     */
    public function testToIntRoundsDecimalScientificNotationString(): void
    {
        $input  = '1.5e2';
        $actual = Cast::toInt($input);

        self::assertSame(150, $actual);
    }

    /**
     * Test that toInt returns the parsed integer when given a numeric string with leading zeros.
     */
    public function testToIntConvertsStringWithLeadingZeros(): void
    {
        $input  = '00042';
        $actual = Cast::toInt($input);

        self::assertSame(42, $actual);
    }

    /**
     * Test that toInt returns the parsed integer when given a numeric string with a leading plus sign.
     */
    public function testToIntConvertsStringWithPlusSign(): void
    {
        $input  = '+42';
        $actual = Cast::toInt($input);

        self::assertSame(42, $actual);
    }

    /**
     * Test that toInt interprets the value as decimal when given an octal-looking string.
     */
    public function testToIntTreatsOctalLikeStringAsDecimal(): void
    {
        $input  = '0777';
        $actual = Cast::toInt($input);

        self::assertSame(777, $actual);
    }

    /**
     * Test that toInt returns 0 when given a hexadecimal-looking string, since it is not numeric.
     */
    public function testToIntConvertsHexLikeStringToZero(): void
    {
        $input  = '0xFF';
        $actual = Cast::toInt($input);

        self::assertSame(0, $actual);
    }

    /**
     * Test that toInt returns 0 when given a numeric string that exceeds the integer range.
     */
    public function testToIntConvertsNumericStringExceedingRangeToZero(): void
    {
        $input  = '9999999999999999999999999999';
        $actual = Cast::toInt($input);

        self::assertSame(0, $actual);
    }

    /**
     * Test that toInt returns 0 when given a numeric string that falls below the integer range.
     */
    public function testToIntConvertsNumericStringBelowRangeToZero(): void
    {
        $input  = '-9999999999999999999999999999';
        $actual = Cast::toInt($input);

        self::assertSame(0, $actual);
    }

    /**
     * Test that toInt returns 0 when given the string "-0".
     */
    public function testToIntConvertsNegativeZeroStringToZero(): void
    {
        $input  = '-0';
        $actual = Cast::toInt($input);

        self::assertSame(0, $actual);
    }

    /**
     * Test that toInt returns 0 when given a closed resource.
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
