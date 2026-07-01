<?php
declare(strict_types=1);

namespace CtwTest\Cast;

use Ctw\Cast\Cast;
use PHPUnit\Framework\TestCase;
use stdClass;

final class ToBoolTest extends TestCase
{
    /**
     * Test that toBool returns true unchanged when given the boolean true.
     */
    public function testToBoolReturnsTrueBooleanUnchanged(): void
    {
        $input  = true;
        $actual = Cast::toBool($input);

        self::assertTrue($actual);
    }

    /**
     * Test that toBool returns false unchanged when given the boolean false.
     */
    public function testToBoolReturnsFalseBooleanUnchanged(): void
    {
        $input  = false;
        $actual = Cast::toBool($input);

        self::assertFalse($actual);
    }

    /**
     * Test that toBool returns true when given the integer 1.
     */
    public function testToBoolConvertsIntegerOneToTrue(): void
    {
        $input  = 1;
        $actual = Cast::toBool($input);

        self::assertTrue($actual);
    }

    /**
     * Test that toBool returns false when given the integer 0.
     */
    public function testToBoolConvertsIntegerZeroToFalse(): void
    {
        $input  = 0;
        $actual = Cast::toBool($input);

        self::assertFalse($actual);
    }

    /**
     * Test that toBool returns false when given the integer 2, which is neither 0 nor 1.
     */
    public function testToBoolConvertsIntegerTwoToFalse(): void
    {
        $input  = 2;
        $actual = Cast::toBool($input);

        self::assertFalse($actual);
    }

    /**
     * Test that toBool returns false when given a negative integer.
     */
    public function testToBoolConvertsNegativeIntegerToFalse(): void
    {
        $input  = -1;
        $actual = Cast::toBool($input);

        self::assertFalse($actual);
    }

    /**
     * Test that toBool returns true when given the float 1.0.
     */
    public function testToBoolConvertsFloatOneToTrue(): void
    {
        $input  = 1.0;
        $actual = Cast::toBool($input);

        self::assertTrue($actual);
    }

    /**
     * Test that toBool returns false when given the float 0.0.
     */
    public function testToBoolConvertsFloatZeroToFalse(): void
    {
        $input  = 0.0;
        $actual = Cast::toBool($input);

        self::assertFalse($actual);
    }

    /**
     * Test that toBool returns false when given the float 1.5, which is not exactly 1.0.
     */
    public function testToBoolConvertsFloatOnePointFiveToFalse(): void
    {
        $input  = 1.5;
        $actual = Cast::toBool($input);

        self::assertFalse($actual);
    }

    /**
     * Test that toBool returns true when given the string "true".
     */
    public function testToBoolConvertsStringTrueToTrue(): void
    {
        $input  = 'true';
        $actual = Cast::toBool($input);

        self::assertTrue($actual);
    }

    /**
     * Test that toBool returns true when given the uppercase string "TRUE".
     */
    public function testToBoolConvertsUppercaseStringTrueToTrue(): void
    {
        $input  = 'TRUE';
        $actual = Cast::toBool($input);

        self::assertTrue($actual);
    }

    /**
     * Test that toBool returns true when given the mixed-case string "TrUe".
     */
    public function testToBoolConvertsMixedCaseStringTrueToTrue(): void
    {
        $input  = 'TrUe';
        $actual = Cast::toBool($input);

        self::assertTrue($actual);
    }

    /**
     * Test that toBool returns true when given the string "1".
     */
    public function testToBoolConvertsStringOneToTrue(): void
    {
        $input  = '1';
        $actual = Cast::toBool($input);

        self::assertTrue($actual);
    }

    /**
     * Test that toBool returns true when given the string "yes".
     */
    public function testToBoolConvertsStringYesToTrue(): void
    {
        $input  = 'yes';
        $actual = Cast::toBool($input);

        self::assertTrue($actual);
    }

    /**
     * Test that toBool returns true when given the string "on".
     */
    public function testToBoolConvertsStringOnToTrue(): void
    {
        $input  = 'on';
        $actual = Cast::toBool($input);

        self::assertTrue($actual);
    }

    /**
     * Test that toBool returns true when given the string "y".
     */
    public function testToBoolConvertsStringYToTrue(): void
    {
        $input  = 'y';
        $actual = Cast::toBool($input);

        self::assertTrue($actual);
    }

    /**
     * Test that toBool returns true when given the string "t".
     */
    public function testToBoolConvertsStringTToTrue(): void
    {
        $input  = 't';
        $actual = Cast::toBool($input);

        self::assertTrue($actual);
    }

    /**
     * Test that toBool returns false when given the string "false".
     */
    public function testToBoolConvertsStringFalseToFalse(): void
    {
        $input  = 'false';
        $actual = Cast::toBool($input);

        self::assertFalse($actual);
    }

    /**
     * Test that toBool returns false when given the uppercase string "FALSE".
     */
    public function testToBoolConvertsUppercaseStringFalseToFalse(): void
    {
        $input  = 'FALSE';
        $actual = Cast::toBool($input);

        self::assertFalse($actual);
    }

    /**
     * Test that toBool returns false when given the string "0".
     */
    public function testToBoolConvertsStringZeroToFalse(): void
    {
        $input  = '0';
        $actual = Cast::toBool($input);

        self::assertFalse($actual);
    }

    /**
     * Test that toBool returns false when given the string "no".
     */
    public function testToBoolConvertsStringNoToFalse(): void
    {
        $input  = 'no';
        $actual = Cast::toBool($input);

        self::assertFalse($actual);
    }

    /**
     * Test that toBool returns false when given the string "off".
     */
    public function testToBoolConvertsStringOffToFalse(): void
    {
        $input  = 'off';
        $actual = Cast::toBool($input);

        self::assertFalse($actual);
    }

    /**
     * Test that toBool returns false when given the string "n".
     */
    public function testToBoolConvertsStringNToFalse(): void
    {
        $input  = 'n';
        $actual = Cast::toBool($input);

        self::assertFalse($actual);
    }

    /**
     * Test that toBool returns false when given the string "f".
     */
    public function testToBoolConvertsStringFToFalse(): void
    {
        $input  = 'f';
        $actual = Cast::toBool($input);

        self::assertFalse($actual);
    }

    /**
     * Test that toBool returns false when given an empty string.
     */
    public function testToBoolConvertsEmptyStringToFalse(): void
    {
        $input  = '';
        $actual = Cast::toBool($input);

        self::assertFalse($actual);
    }

    /**
     * Test that toBool returns false when given a string containing only whitespace.
     */
    public function testToBoolConvertsWhitespaceOnlyStringToFalse(): void
    {
        $input  = '   ';
        $actual = Cast::toBool($input);

        self::assertFalse($actual);
    }

    /**
     * Test that toBool trims surrounding whitespace when given a padded truthy string.
     */
    public function testToBoolTrimsWhitespaceFromStringBeforeConversion(): void
    {
        $input  = '  true  ';
        $actual = Cast::toBool($input);

        self::assertTrue($actual);
    }

    /**
     * Test that toBool returns false when given an unrecognized string such as "maybe".
     */
    public function testToBoolConvertsInvalidStringToFalse(): void
    {
        $input  = 'maybe';
        $actual = Cast::toBool($input);

        self::assertFalse($actual);
    }

    /**
     * Test that toBool returns false when given a numeric string other than "0" or "1".
     */
    public function testToBoolConvertsNumericStringOtherThanZeroOrOneToFalse(): void
    {
        $input  = '2';
        $actual = Cast::toBool($input);

        self::assertFalse($actual);
    }

    /**
     * Test that toBool returns false when given null.
     */
    public function testToBoolConvertsNullToFalse(): void
    {
        $input  = null;
        $actual = Cast::toBool($input);

        self::assertFalse($actual);
    }

    /**
     * Test that toBool returns false when given an array.
     */
    public function testToBoolConvertsArrayToFalse(): void
    {
        $input  = [true, false];
        $actual = Cast::toBool($input);

        self::assertFalse($actual);
    }

    /**
     * Test that toBool returns false when given an object.
     */
    public function testToBoolConvertsObjectToFalse(): void
    {
        $input  = new stdClass();
        $actual = Cast::toBool($input);

        self::assertFalse($actual);
    }

    /**
     * Test that toBool returns true when given the uppercase string "YES".
     */
    public function testToBoolConvertsUppercaseYesToTrue(): void
    {
        $input  = 'YES';
        $actual = Cast::toBool($input);

        self::assertTrue($actual);
    }

    /**
     * Test that toBool returns false when given the uppercase string "NO".
     */
    public function testToBoolConvertsUppercaseNoToFalse(): void
    {
        $input  = 'NO';
        $actual = Cast::toBool($input);

        self::assertFalse($actual);
    }

    /**
     * Test that toBool returns true when given the uppercase string "ON".
     */
    public function testToBoolConvertsUppercaseOnToTrue(): void
    {
        $input  = 'ON';
        $actual = Cast::toBool($input);

        self::assertTrue($actual);
    }

    /**
     * Test that toBool returns false when given the uppercase string "OFF".
     */
    public function testToBoolConvertsUppercaseOffToFalse(): void
    {
        $input  = 'OFF';
        $actual = Cast::toBool($input);

        self::assertFalse($actual);
    }

    /**
     * Test that toBool returns true when given the uppercase string "Y".
     */
    public function testToBoolConvertsUppercaseYToTrue(): void
    {
        $input  = 'Y';
        $actual = Cast::toBool($input);

        self::assertTrue($actual);
    }

    /**
     * Test that toBool returns false when given the uppercase string "N".
     */
    public function testToBoolConvertsUppercaseNToFalse(): void
    {
        $input  = 'N';
        $actual = Cast::toBool($input);

        self::assertFalse($actual);
    }

    /**
     * Test that toBool returns true when given the uppercase string "T".
     */
    public function testToBoolConvertsUppercaseTToTrue(): void
    {
        $input  = 'T';
        $actual = Cast::toBool($input);

        self::assertTrue($actual);
    }

    /**
     * Test that toBool returns false when given the uppercase string "F".
     */
    public function testToBoolConvertsUppercaseFToFalse(): void
    {
        $input  = 'F';
        $actual = Cast::toBool($input);

        self::assertFalse($actual);
    }

    /**
     * Test that toBool returns false when given a string containing only a tab character.
     */
    public function testToBoolConvertsTabCharacterToFalse(): void
    {
        $input  = "\t";
        $actual = Cast::toBool($input);

        self::assertFalse($actual);
    }

    /**
     * Test that toBool returns false when given a string containing only a newline character.
     */
    public function testToBoolConvertsNewlineCharacterToFalse(): void
    {
        $input  = "\n";
        $actual = Cast::toBool($input);

        self::assertFalse($actual);
    }

    /**
     * Test that toBool returns false when given an unrecognized value padded with whitespace.
     */
    public function testToBoolConvertsStringWithWhitespaceAndInvalidValueToFalse(): void
    {
        $input  = '  invalid  ';
        $actual = Cast::toBool($input);

        self::assertFalse($actual);
    }

    /**
     * Test that toBool returns false when given a positive float other than 1.0.
     */
    public function testToBoolConvertsPositiveFloatOtherThanOneToFalse(): void
    {
        $input  = 2.0;
        $actual = Cast::toBool($input);

        self::assertFalse($actual);
    }

    /**
     * Test that toBool returns false when given the negative float -1.0.
     */
    public function testToBoolConvertsNegativeFloatToFalse(): void
    {
        $input  = -1.0;
        $actual = Cast::toBool($input);

        self::assertFalse($actual);
    }

    /**
     * Test that toBool returns false when given PHP_INT_MAX, which is neither 0 nor 1.
     */
    public function testToBoolConvertsMaxIntToFalse(): void
    {
        $input  = PHP_INT_MAX;
        $actual = Cast::toBool($input);

        self::assertFalse($actual);
    }

    /**
     * Test that toBool returns false when given a closed resource.
     */
    public function testToBoolConvertsClosedResourceToFalse(): void
    {
        $resource = fopen('php://memory', 'r');
        self::assertIsResource($resource);
        fclose($resource);

        $actual = Cast::toBool($resource);

        self::assertFalse($actual);
    }
}
