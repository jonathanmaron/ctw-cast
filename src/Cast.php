<?php
declare(strict_types=1);

namespace Ctw\Cast;

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
}
