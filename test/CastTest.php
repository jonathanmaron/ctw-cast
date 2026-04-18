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
     * Test that Cast is declared final to prevent extension.
     */
    public function testCastClassIsDeclaredFinal(): void
    {
        $reflection = new ReflectionClass(Cast::class);

        self::assertTrue($reflection->isFinal());
    }

    /**
     * Test that toArray is a public static method returning array.
     */
    public function testToArrayIsPublicStaticMethodReturningArray(): void
    {
        $this->assertPublicStaticReturnType('toArray', 'array');
    }

    /**
     * Test that toBool is a public static method returning bool.
     */
    public function testToBoolIsPublicStaticMethodReturningBool(): void
    {
        $this->assertPublicStaticReturnType('toBool', 'bool');
    }

    /**
     * Test that toFloat is a public static method returning float.
     */
    public function testToFloatIsPublicStaticMethodReturningFloat(): void
    {
        $this->assertPublicStaticReturnType('toFloat', 'float');
    }

    /**
     * Test that toInt is a public static method returning int.
     */
    public function testToIntIsPublicStaticMethodReturningInt(): void
    {
        $this->assertPublicStaticReturnType('toInt', 'int');
    }

    /**
     * Test that toJson is a public static method returning string.
     */
    public function testToJsonIsPublicStaticMethodReturningString(): void
    {
        $this->assertPublicStaticReturnType('toJson', 'string');
    }

    /**
     * Test that toString is a public static method returning string.
     */
    public function testToStringIsPublicStaticMethodReturningString(): void
    {
        $this->assertPublicStaticReturnType('toString', 'string');
    }

    /**
     * Test that toArray on a non-castable resource returns the documented [] default.
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
     * Test that toBool on a non-castable value returns the documented false default.
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
     * Test that toFloat on a non-castable value returns the documented 0.0 default.
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
     * Test that toInt on a non-castable value returns the documented 0 default.
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
     * Test that toJson on a non-castable value returns the documented "{}" default.
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
     * Test that toString on a non-castable value returns the documented "" default.
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
}
