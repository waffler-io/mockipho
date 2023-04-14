<?php

namespace Waffler\Mockipho;

use Mockery;
use Mockery\LegacyMockInterface;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use ZEngine\Reflection\ReflectionClass as ZReflectionClass;

/**
 * Class MockProxy.
 *
 * @author ErickJMenezes <erickmenezes.dev@gmail.com>
 * @template-covariant T
 * @mixin T
 */
class MockProxy
{
    /**
     * @param T&(MockInterface|LegacyMockInterface) $mockiphoMock
     */
    public function __construct(
        private $mockiphoMock
    )
    {
    }

    public function __call(string $name, array $arguments): mixed
    {
        if (!$this->mockipho_isCalledFromTestCase() || $this->mockipho_isMethodOfMockery($name)) {
            return $this->mockiphoMock->$name(...$arguments);
        }
        return new MethodCall(
            $this->mockiphoMock,
            $name,
            $arguments
        );
    }

    private function mockipho_isCalledFromTestCase(): bool
    {
        $class = debug_backtrace(limit: 4)[2]["class"] ?? null;
        if (is_null($class)) {
            return false;
        }
        return is_a(
            $class,
            TestCase::class,
            true,
        );
    }

    private function mockipho_isMethodOfMockery(string $method): bool
    {
        return method_exists(MockInterface::class, $method)
            || method_exists(LegacyMockInterface::class, $method);
    }

    /**
     * @param class-string<MockType> $mockiphoType
     *
     * @return self<MockType>
     * @throws \ReflectionException
     * @author   ErickJMenezes <erickmenezes.dev@gmail.com>
     * @template MockType of object
     */
    public static function create(string $mockiphoType): self
    {
        $mock = new self(Mockery::mock($mockiphoType));
        $self = new ZReflectionClass($mock);
        if (interface_exists($mockiphoType)) {
            $self->addInterfaces($mockiphoType);
        } else {
            throw new \RuntimeException("Currently only interface are supported, the type [$mockiphoType] cannot be mocked.");
        }
        $self->addInterfaces(MockInterface::class, LegacyMockInterface::class);
        return $mock;
    }
}
