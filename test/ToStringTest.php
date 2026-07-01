<?php
declare(strict_types=1);

namespace CtwTest\Cast;

use Ctw\Cast\Cast;
use PHPUnit\Framework\TestCase;
use stdClass;
use Stringable;

final class ToStringTest extends TestCase
{
    /**
     * Test that toString returns the string unchanged when given a plain string.
     */
    public function testToStringReturnsStringValueUnchanged(): void
    {
        $input  = 'hello world';
        $actual = Cast::toString($input);

        self::assertSame('hello world', $actual);
    }

    /**
     * Test that toString returns an empty string unchanged when given an empty string.
     */
    public function testToStringReturnsEmptyStringUnchanged(): void
    {
        $input  = '';
        $actual = Cast::toString($input);

        self::assertSame('', $actual);
    }

    /**
     * Test that toString preserves internal whitespace when given a string with padding and newlines.
     */
    public function testToStringPreservesWhitespaceInString(): void
    {
        $input  = "  hello  \n  world  ";
        $actual = Cast::toString($input);

        self::assertSame("  hello  \n  world  ", $actual);
    }

    /**
     * Test that toString returns the decimal representation when given a positive integer.
     */
    public function testToStringConvertsPositiveIntegerToString(): void
    {
        $input  = 42;
        $actual = Cast::toString($input);

        self::assertSame('42', $actual);
    }

    /**
     * Test that toString returns the decimal representation when given a negative integer.
     */
    public function testToStringConvertsNegativeIntegerToString(): void
    {
        $input  = -42;
        $actual = Cast::toString($input);

        self::assertSame('-42', $actual);
    }

    /**
     * Test that toString returns "0" when given the integer zero.
     */
    public function testToStringConvertsZeroToString(): void
    {
        $input  = 0;
        $actual = Cast::toString($input);

        self::assertSame('0', $actual);
    }

    /**
     * Test that toString returns the decimal representation when given a positive float.
     */
    public function testToStringConvertsPositiveFloatToString(): void
    {
        $input  = 3.14;
        $actual = Cast::toString($input);

        self::assertSame('3.14', $actual);
    }

    /**
     * Test that toString returns the decimal representation when given a negative float.
     */
    public function testToStringConvertsNegativeFloatToString(): void
    {
        $input  = -3.14;
        $actual = Cast::toString($input);

        self::assertSame('-3.14', $actual);
    }

    /**
     * Test that toString returns "0" when given the float zero.
     */
    public function testToStringConvertsZeroFloatToString(): void
    {
        $input  = 0.0;
        $actual = Cast::toString($input);

        self::assertSame('0', $actual);
    }

    /**
     * Test that toString returns the expanded decimal representation when given a float in scientific notation.
     */
    public function testToStringConvertsFloatWithScientificNotation(): void
    {
        $input  = 1.23e-4;
        $actual = Cast::toString($input);

        self::assertStringContainsString('0.000123', $actual);
    }

    /**
     * Test that toString returns "1" when given the boolean true.
     */
    public function testToStringConvertsTrueBooleanToOne(): void
    {
        $input  = true;
        $actual = Cast::toString($input);

        self::assertSame('1', $actual);
    }

    /**
     * Test that toString returns "0" when given the boolean false.
     */
    public function testToStringConvertsFalseBooleanToZero(): void
    {
        $input  = false;
        $actual = Cast::toString($input);

        self::assertSame('0', $actual);
    }

    /**
     * Test that toString returns an empty string when given null.
     */
    public function testToStringConvertsNullToEmptyString(): void
    {
        $input  = null;
        $actual = Cast::toString($input);

        self::assertSame('', $actual);
    }

    /**
     * Test that toString returns the magic method output when given an object implementing __toString.
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
     * Test that toString returns the string representation when given an object implementing Stringable.
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
     * Test that toString returns an empty string when given an object without a __toString method.
     */
    public function testToStringConvertsObjectWithoutToStringMethodToEmptyString(): void
    {
        $object = new stdClass();
        $actual = Cast::toString($object);

        self::assertSame('', $actual);
    }

    /**
     * Test that toString returns an empty string when given a non-empty array.
     */
    public function testToStringConvertsArrayToEmptyString(): void
    {
        $input  = ['hello', 'world'];
        $actual = Cast::toString($input);

        self::assertSame('', $actual);
    }

    /**
     * Test that toString returns an empty string when given an empty array.
     */
    public function testToStringConvertsEmptyArrayToEmptyString(): void
    {
        $input  = [];
        $actual = Cast::toString($input);

        self::assertSame('', $actual);
    }

    /**
     * Test that toString returns the decimal representation when given PHP_INT_MAX.
     */
    public function testToStringConvertsVeryLargeInteger(): void
    {
        $input  = PHP_INT_MAX;
        $actual = Cast::toString($input);

        self::assertSame((string) PHP_INT_MAX, $actual);
    }

    /**
     * Test that toString returns the decimal representation when given PHP_INT_MIN.
     */
    public function testToStringConvertsVerySmallInteger(): void
    {
        $input  = PHP_INT_MIN;
        $actual = Cast::toString($input);

        self::assertSame((string) PHP_INT_MIN, $actual);
    }

    /**
     * Test that toString preserves multibyte characters when given a Unicode string.
     */
    public function testToStringPreservesUnicodeString(): void
    {
        $input  = '你好世界 🌍';
        $actual = Cast::toString($input);

        self::assertSame('你好世界 🌍', $actual);
    }

    /**
     * Test that toString preserves control characters when given a string with escape sequences.
     */
    public function testToStringPreservesSpecialCharacters(): void
    {
        $input  = "Hello\nWorld\tTab\r\nNewline";
        $actual = Cast::toString($input);

        self::assertSame("Hello\nWorld\tTab\r\nNewline", $actual);
    }

    /**
     * Test that toString returns the string unchanged when given a numeric string.
     */
    public function testToStringPreservesNumericString(): void
    {
        $input  = '123';
        $actual = Cast::toString($input);

        self::assertSame('123', $actual);
    }

    /**
     * Test that toString returns the string unchanged when given a decimal-looking string.
     */
    public function testToStringPreservesFloatString(): void
    {
        $input  = '3.14';
        $actual = Cast::toString($input);

        self::assertSame('3.14', $actual);
    }

    /**
     * Test that toString returns "INF" when given positive infinity.
     */
    public function testToStringConvertsInfinityToString(): void
    {
        $input  = INF;
        $actual = Cast::toString($input);

        self::assertSame('INF', $actual);
    }

    /**
     * Test that toString returns "-INF" when given negative infinity.
     */
    public function testToStringConvertsNegativeInfinityToString(): void
    {
        $input  = -INF;
        $actual = Cast::toString($input);

        self::assertSame('-INF', $actual);
    }

    /**
     * Test that toString returns "NAN" when given a NaN float.
     */
    public function testToStringConvertsNaNToString(): void
    {
        $input  = NAN;
        $actual = Cast::toString($input);

        self::assertSame('NAN', $actual);
    }

    /**
     * Test that toString returns "NAN" without raising a PHP warning when given a NaN float.
     *
     * PHP 8.5 emits an E_WARNING ("unexpected NAN value was coerced to string")
     * when NAN is cast with (string). The non-finite guard must avoid that cast.
     */
    public function testToStringConvertsNaNWithoutRaisingWarning(): void
    {
        $actual = '';
        $errors = $this->captureErrors(static function () use (&$actual): void {
            $actual = Cast::toString(NAN);
        });

        self::assertSame('NAN', $actual);
        self::assertSame([], $errors);
    }

    /**
     * Test that toString returns "INF" without raising a PHP warning when given positive infinity.
     *
     * PHP 8.5 emits an E_WARNING when INF is cast with (string). The non-finite
     * guard must avoid that cast.
     */
    public function testToStringConvertsInfinityWithoutRaisingWarning(): void
    {
        $actual = '';
        $errors = $this->captureErrors(static function () use (&$actual): void {
            $actual = Cast::toString(INF);
        });

        self::assertSame('INF', $actual);
        self::assertSame([], $errors);
    }

    /**
     * Test that toString returns "-INF" without raising a PHP warning when given negative infinity.
     */
    public function testToStringConvertsNegativeInfinityWithoutRaisingWarning(): void
    {
        $actual = '';
        $errors = $this->captureErrors(static function () use (&$actual): void {
            $actual = Cast::toString(-INF);
        });

        self::assertSame('-INF', $actual);
        self::assertSame([], $errors);
    }

    /**
     * Test that toString returns "NAN" when given a computed NaN rather than the literal constant.
     *
     * Confirms the guard relies on is_nan() rather than identity with the NAN
     * constant.
     */
    public function testToStringConvertsComputedNaNToString(): void
    {
        $input = INF - INF;
        self::assertNan($input);

        $actual = Cast::toString($input);

        self::assertSame('NAN', $actual);
    }

    /**
     * Test that toString returns "INF" when given a computed infinity produced by float overflow.
     *
     * Confirms the guard relies on is_finite() rather than identity with the INF
     * constant.
     */
    public function testToStringConvertsComputedInfinityToString(): void
    {
        $input = PHP_FLOAT_MAX * 2.0;
        self::assertInfinite($input);

        $actual = Cast::toString($input);

        self::assertSame('INF', $actual);
    }

    /**
     * Test that toString returns "-INF" when given a computed negative infinity produced by float overflow.
     */
    public function testToStringConvertsComputedNegativeInfinityToString(): void
    {
        $input = -PHP_FLOAT_MAX * 2.0;
        self::assertInfinite($input);

        $actual = Cast::toString($input);

        self::assertSame('-INF', $actual);
    }

    /**
     * Test that toString returns an empty string when given a Stringable object whose __toString returns an empty string.
     */
    public function testToStringConvertsStringableObjectReturningEmptyString(): void
    {
        $object = new class() implements Stringable {
            public function __toString(): string
            {
                return '';
            }
        };

        $actual = Cast::toString($object);

        self::assertSame('', $actual);
    }

    /**
     * Test that toString returns an empty string when given a closed resource.
     */
    public function testToStringConvertsClosedResourceToEmptyString(): void
    {
        $resource = fopen('php://memory', 'r');
        self::assertIsResource($resource);
        fclose($resource);

        $actual = Cast::toString($resource);

        self::assertSame('', $actual);
    }

    /**
     * Run the given callback and collect any PHP error messages it raises.
     *
     * @param callable():void $callback
     *
     * @return list<string> the messages raised, in order (empty when none)
     */
    private function captureErrors(callable $callback): array
    {
        $errors = [];

        set_error_handler(static function (int $errno, string $errstr) use (&$errors): bool {
            $errors[] = $errstr;

            return true;
        });

        try {
            $callback();
        } finally {
            restore_error_handler();
        }

        return $errors;
    }
}
