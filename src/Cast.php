<?php
declare(strict_types=1);

namespace Ctw\Cast;

use Ctw\Cast\Exception\CastException;

/**
 * Type-safe casting utility class.
 *
 * Provides static methods for converting values between different types
 * with comprehensive error handling and edge case support.
 */
final class Cast
{
    use ToArrayTrait;
    use ToBoolTrait;
    use ToFloatTrait;
    use ToIntTrait;
    use ToJsonTrait;
    use ToStringTrait;

    private const int    INT_TRUE     = 1;

    private const int    INT_FALSE    = 0;

    private const float  FLOAT_TRUE   = 1.0;

    private const float  FLOAT_FALSE  = 0.0;

    private const string STRING_TRUE  = '1';

    private const string STRING_FALSE = '0';

    private const string EMPTY_STRING = '';

    /**
     * @throws CastException
     */
    private static function throwCastException(string $format, bool|float|int|string|null ...$args): never
    {
        throw new CastException(sprintf($format, ...$args));
    }
}
