<?php

declare(strict_types=1);

/*
 * This file is part of Waffler\Mockipho.
 * (c) Erick Johnson Almeida de Menezes <erickmenezes.dev@gmail.com>
 * This source file is subject to the MIT licence that is bundled
 * with this source code in the file LICENCE.
 */

namespace Waffler\Mockipho\Loaders;

use Mockery;
use Mockery\HigherOrderMessage;
use Mockery\LegacyMockInterface;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionNamedType;
use Waffler\Mockipho\Exceptions\IllegalPropertyException;
use Waffler\Mockipho\MethodCall;
use Waffler\Mockipho\Mock;
use ZEngine\Reflection\ReflectionClass as ZReflectionClass;

/**
 * Class MockLoader.
 *
 * @author ErickJMenezes <erickmenezes.dev@gmail.com>
 */
class MockLoader
{
    public function load(object $object): void
    {
        $reflection = new ReflectionClass($object);
        $properties = $reflection->getProperties();
        foreach ($properties as $property) {
            if (empty($property->getAttributes(Mock::class))) {
                continue;
            }
            $property->setAccessible(true);
            $reflectionType = $property->getType();
            if (!$reflectionType instanceof ReflectionNamedType) {
                throw new IllegalPropertyException(
                    $property,
                    'Mock property must have a type. Intersection and Union types are not supported.'
                );
            }
            if (!class_exists($reflectionType->getName()) && !interface_exists($reflectionType->getName())) {
                throw new IllegalPropertyException(
                    $property,
                    "[{$reflectionType->getName()}] is not a valid class or interface."
                );
            }
            $mock = $this->createMockProxy($reflectionType->getName());
            $property->setValue($object, $mock);
        }
    }

    private function createMockProxy(string $type): object
    {
        return new class ($type) {
            private MockInterface|HigherOrderMessage|LegacyMockInterface $mockipho_mock;

            public function __construct(public string $mockipho_type)
            {
                $this->mockipho_mock = Mockery::mock($mockipho_type);
                $this->mockipho_loadMock();
            }

            public function __call(string $name, array $arguments): mixed
            {
                if (!$this->mockipho_isCalledFromTestCase() || $this->mockipho_isMethodOfMockery($name)) {
                    return $this->mockipho_mock->$name(...$arguments);
                }
                return new MethodCall(
                    $this->mockipho_mock,
                    $name,
                    $arguments
                );
            }

            private function mockipho_isCalledFromTestCase(): bool
            {
                return is_a(debug_backtrace(limit: 4)[3]["class"] ?? "", TestCase::class, true);
            }

            private function mockipho_isMethodOfMockery(string $method): bool
            {
                return method_exists(MockInterface::class, $method)
                    || method_exists(LegacyMockInterface::class, $method);
            }

            private function mockipho_loadMock(): void
            {
                $self = new ZReflectionClass($this);
                if (interface_exists($this->mockipho_type)) {
                    $self->addInterfaces($this->mockipho_type);
                } else {
                    throw new \RuntimeException("Currently only interface are supported, the type [{$this->mockipho_type}] cannot be mocked.");
                }
                $self->addInterfaces(MockInterface::class, LegacyMockInterface::class);
            }
        };
    }
}
