<?php
declare(strict_types=1);

namespace CtwTest\Cast;

use Ctw\Cast\Cast;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionMethod;

final class CastTest extends TestCase
{
    /**
     * Test that the Cast class reports as final when inspected via reflection.
     */
    public function testCastClassIsDeclaredFinal(): void
    {
        $reflection = new ReflectionClass(Cast::class);

        self::assertTrue($reflection->isFinal());
    }

    /**
     * Test that toArray is a public static method declaring an array return type when inspected via reflection.
     */
    public function testToArrayIsPublicStaticMethodReturningArray(): void
    {
        $this->assertPublicStaticReturnType('toArray', 'array');
    }

    /**
     * Test that toBool is a public static method declaring a bool return type when inspected via reflection.
     */
    public function testToBoolIsPublicStaticMethodReturningBool(): void
    {
        $this->assertPublicStaticReturnType('toBool', 'bool');
    }

    /**
     * Test that toFloat is a public static method declaring a float return type when inspected via reflection.
     */
    public function testToFloatIsPublicStaticMethodReturningFloat(): void
    {
        $this->assertPublicStaticReturnType('toFloat', 'float');
    }

    /**
     * Test that toInt is a public static method declaring an int return type when inspected via reflection.
     */
    public function testToIntIsPublicStaticMethodReturningInt(): void
    {
        $this->assertPublicStaticReturnType('toInt', 'int');
    }

    /**
     * Test that toJson is a public static method declaring a string return type when inspected via reflection.
     */
    public function testToJsonIsPublicStaticMethodReturningString(): void
    {
        $this->assertPublicStaticReturnType('toJson', 'string');
    }

    /**
     * Test that toString is a public static method declaring a string return type when inspected via reflection.
     */
    public function testToStringIsPublicStaticMethodReturningString(): void
    {
        $this->assertPublicStaticReturnType('toString', 'string');
    }

    /**
     * Test that toArray is annotated with #[\NoDiscard] so discarding its return value warns callers.
     */
    public function testToArrayIsMarkedNoDiscard(): void
    {
        $this->assertMethodIsMarkedNoDiscard('toArray');
    }

    /**
     * Test that toBool is annotated with #[\NoDiscard] so discarding its return value warns callers.
     */
    public function testToBoolIsMarkedNoDiscard(): void
    {
        $this->assertMethodIsMarkedNoDiscard('toBool');
    }

    /**
     * Test that toFloat is annotated with #[\NoDiscard] so discarding its return value warns callers.
     */
    public function testToFloatIsMarkedNoDiscard(): void
    {
        $this->assertMethodIsMarkedNoDiscard('toFloat');
    }

    /**
     * Test that toInt is annotated with #[\NoDiscard] so discarding its return value warns callers.
     */
    public function testToIntIsMarkedNoDiscard(): void
    {
        $this->assertMethodIsMarkedNoDiscard('toInt');
    }

    /**
     * Test that toJson is annotated with #[\NoDiscard] so discarding its return value warns callers.
     */
    public function testToJsonIsMarkedNoDiscard(): void
    {
        $this->assertMethodIsMarkedNoDiscard('toJson');
    }

    /**
     * Test that toString is annotated with #[\NoDiscard] so discarding its return value warns callers.
     */
    public function testToStringIsMarkedNoDiscard(): void
    {
        $this->assertMethodIsMarkedNoDiscard('toString');
    }

    /**
     * Test that toArray returns the documented empty array default when given a non-castable open resource.
     */
    public function testToArrayReturnsEmptyArrayDefaultForNonCastableValue(): void
    {
        $resource = fopen('php://memory', 'r');
        self::assertIsResource($resource);

        try {
            $actual = Cast::toArray($resource);
        } finally {
            fclose($resource);
        }

        self::assertSame([], $actual);
    }

    /**
     * Test that toBool returns the documented false default when given a non-castable open resource.
     */
    public function testToBoolReturnsFalseDefaultForNonCastableValue(): void
    {
        $resource = fopen('php://memory', 'r');
        self::assertIsResource($resource);

        try {
            $actual = Cast::toBool($resource);
        } finally {
            fclose($resource);
        }

        self::assertFalse($actual);
    }

    /**
     * Test that toFloat returns the documented 0.0 default when given a non-castable open resource.
     */
    public function testToFloatReturnsZeroDefaultForNonCastableValue(): void
    {
        $resource = fopen('php://memory', 'r');
        self::assertIsResource($resource);

        try {
            $actual = Cast::toFloat($resource);
        } finally {
            fclose($resource);
        }

        self::assertSame(0.0, $actual);
    }

    /**
     * Test that toInt returns the documented 0 default when given a non-castable open resource.
     */
    public function testToIntReturnsZeroDefaultForNonCastableValue(): void
    {
        $resource = fopen('php://memory', 'r');
        self::assertIsResource($resource);

        try {
            $actual = Cast::toInt($resource);
        } finally {
            fclose($resource);
        }

        self::assertSame(0, $actual);
    }

    /**
     * Test that toJson returns the documented "{}" default when given a non-castable open resource.
     */
    public function testToJsonReturnsEmptyJsonObjectDefaultForNonCastableValue(): void
    {
        $resource = fopen('php://memory', 'r');
        self::assertIsResource($resource);

        try {
            $actual = Cast::toJson($resource);
        } finally {
            fclose($resource);
        }

        self::assertSame('{}', $actual);
    }

    /**
     * Test that toString returns the documented empty string default when given a non-castable open resource.
     */
    public function testToStringReturnsEmptyStringDefaultForNonCastableValue(): void
    {
        $resource = fopen('php://memory', 'r');
        self::assertIsResource($resource);

        try {
            $actual = Cast::toString($resource);
        } finally {
            fclose($resource);
        }

        self::assertSame('', $actual);
    }

    /**
     * Assert that a method on Cast is public, static, and declares the given return type.
     */
    private function assertPublicStaticReturnType(string $method, string $expectedReturnType): void
    {
        $reflection = new ReflectionMethod(Cast::class, $method);

        self::assertTrue($reflection->isPublic(), sprintf('%s must be public', $method));
        self::assertTrue($reflection->isStatic(), sprintf('%s must be static', $method));

        $returnType = $reflection->getReturnType();
        self::assertNotNull($returnType, sprintf('%s must declare a return type', $method));
        self::assertSame($expectedReturnType, (string) $returnType);
    }

    /**
     * Assert that a method on Cast carries exactly one native #[\NoDiscard]
     * attribute with a non-empty consumer-facing message.
     */
    private function assertMethodIsMarkedNoDiscard(string $method): void
    {
        $reflection = new ReflectionMethod(Cast::class, $method);

        $attributes = $reflection->getAttributes(\NoDiscard::class);
        self::assertCount(1, $attributes, sprintf('%s must carry exactly one #[\NoDiscard] attribute', $method));

        $message = $attributes[0]->newInstance()->message;
        self::assertNotNull($message, sprintf('%s #[\NoDiscard] must supply a message', $method));
        self::assertNotSame('', $message, sprintf('%s #[\NoDiscard] message must not be empty', $method));
    }
}
