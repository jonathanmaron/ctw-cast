<?php
declare(strict_types=1);

namespace CtwTest\Cast;

use Ctw\Cast\Cast;
use Ctw\Cast\Exception\CastException;
use PHPUnit\Framework\TestCase;
use stdClass;

final class ToBoolTest extends TestCase
{
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
        $this->expectExceptionMessage('cannot be cast to bool');

        Cast::toBool($input);
    }

    /**
     * Test that numeric string other than 0 or 1 throws exception
     */
    public function testToBoolThrowsExceptionForNumericStringOtherThanZeroOrOne(): void
    {
        $input = '2';

        $this->expectException(CastException::class);
        $this->expectExceptionMessage('cannot be cast to bool');

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
        $this->expectExceptionMessage('cannot be cast to bool');

        Cast::toBool($input);
    }

    /**
     * Test that object throws exception
     */
    public function testToBoolThrowsExceptionForObject(): void
    {
        $input = new stdClass();

        $this->expectException(CastException::class);
        $this->expectExceptionMessage('cannot be cast to bool');

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
        $this->expectExceptionMessage('cannot be cast to bool');

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
}
