<?php
declare(strict_types=1);

namespace Ctw\Cast;

/**
 * Type-safe casting utility class.
 *
 * Provides static methods for converting values between different types.
 * Methods never throw exceptions; instead they return a safe default value
 * when a value cannot be cast to the target type.
 */
final class Cast
{
    use ToArrayTrait;
    use ToBoolTrait;
    use ToFloatTrait;
    use ToIntTrait;
    use ToJsonTrait;
    use ToStringTrait;

    private const array  EMPTY_ARRAY  = [];

    private const bool   EMPTY_BOOL   = false;

    private const float  EMPTY_FLOAT  = 0.0;

    private const int    EMPTY_INT    = 0;

    private const string EMPTY_JSON   = '{}';

    private const string EMPTY_STRING = '';

    private const bool   BOOL_TRUE    = true;

    private const int    INT_TRUE     = 1;

    private const float  FLOAT_TRUE   = 1.0;

    private const string STRING_TRUE  = '1';

    private const string STRING_FALSE = '0';
}
