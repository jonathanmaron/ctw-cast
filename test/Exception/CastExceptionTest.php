<?php
declare(strict_types=1);

namespace CtwTest\Cast\Exception;

use Ctw\Cast\Exception\CastException;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class CastExceptionTest extends TestCase
{
    /**
     * Test that exception extends InvalidArgumentException
     */
    public function testExceptionExtendsInvalidArgumentException(): void
    {
        $exception = new CastException('Test message');

        // @phpstan-ignore-next-line
        self::assertInstanceOf(InvalidArgumentException::class, $exception);
    }

    /**
     * Test that exception can be instantiated with message
     */
    public function testExceptionCanBeInstantiatedWithMessage(): void
    {
        $message   = 'Value cannot be cast to int';
        $exception = new CastException($message);

        self::assertSame($message, $exception->getMessage());
    }

    /**
     * Test that exception can be instantiated with code
     */
    public function testExceptionCanBeInstantiatedWithCode(): void
    {
        $message   = 'Test message';
        $code      = 42;
        $exception = new CastException($message, $code);

        self::assertSame($code, $exception->getCode());
    }

    /**
     * Test that exception can be instantiated with previous exception
     */
    public function testExceptionCanBeInstantiatedWithPreviousException(): void
    {
        $previous  = new \RuntimeException('Previous exception');
        $exception = new CastException('Test message', 0, $previous);

        self::assertSame($previous, $exception->getPrevious());
    }

    /**
     * Test that exception can be thrown and caught
     */
    public function testExceptionCanBeThrownAndCaught(): void
    {
        $this->expectException(CastException::class);
        $this->expectExceptionMessage('Cannot cast value');

        throw new CastException('Cannot cast value');
    }

    /**
     * Test that exception can be caught as InvalidArgumentException
     */
    public function testExceptionCanBeCaughtAsInvalidArgumentException(): void
    {
        $this->expectException(InvalidArgumentException::class);

        throw new CastException('Test message');
    }

    /**
     * Test that exception default code is zero
     */
    public function testExceptionDefaultCodeIsZero(): void
    {
        $exception = new CastException('Test message');

        self::assertSame(0, $exception->getCode());
    }

    /**
     * Test that exception default previous is null
     */
    public function testExceptionDefaultPreviousIsNull(): void
    {
        $exception = new CastException('Test message');

        self::assertNull($exception->getPrevious());
    }
}
