<?php
declare(strict_types=1);

namespace Ctw\Cast\Exception;

use InvalidArgumentException;

/**
 * Exception thrown when a value cannot be cast to the target type.
 *
 * This exception is thrown by Cast class methods when type conversion
 * fails due to incompatible types, invalid values, or overflow conditions.
 */
class CastException extends InvalidArgumentException
{
}
