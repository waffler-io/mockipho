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
use ReflectionClass;
use ReflectionNamedType;
use Waffler\Mockipho\Exceptions\IllegalPropertyException;
use Waffler\Mockipho\MethodCall;
use Waffler\Mockipho\Mock;
use Waffler\Mockipho\TestCase;
use WeakMap;

/**
 * Class MockLoader.
 *
 * @author ErickJMenezes <erickmenezes.dev@gmail.com>
 */
class MockLoader
{
    private WeakMap $mocks;

    public function __construct()
    {
        /** @psalm-suppress PropertyTypeCoercion */
        $this->mocks = new WeakMap();
    }

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
            $mock = $this->resolveOrCreateMock($reflectionType->getName());
            $property->setValue($object, $mock);
        }
    }

    /**
     * @param string $className
     *
     * @return object
     * @throws \ReflectionException
     * @author ErickJMenezes <erickmenezes.dev@gmail.com>
     */
    private function resolveOrCreateMock(string $className): object
    {
        foreach ($this->mocks as $mock => $mockClass) {
            if ($mockClass === $className) {
                return $mock;
            }
        }
        $mock = $this->createMockProxy(Mockery::mock($className));
        $zReflection = new \ZEngine\Reflection\ReflectionClass($mock);

        if (interface_exists($className)) {
            $zReflection->addInterfaces($className);
        } elseif (class_exists($className)) {
            $zReflection->setParent($className);
            foreach (
                $zReflection->getParentClass()
                    ->getMethods() as $reflectionMethod
            ) {
                if (in_array($reflectionMethod->getName(), ['__call', '__get', '__set'], true)) {
                    continue;
                }
                $zReflection->removeMethods($reflectionMethod->getName());
            }
        }
        $zReflection->addInterfaces(MockInterface::class, LegacyMockInterface::class);

        $this->mocks[$mock] = $className;
        return $mock;
    }

    private function createMockProxy(MockInterface|HigherOrderMessage|LegacyMockInterface $mock): object
    {
        return new class ($mock) {
            public function __construct(
                public MockInterface|HigherOrderMessage|LegacyMockInterface $mockeryBaseMock
            ) {
            }

            public function __call(string $name, array $arguments): mixed
            {
                if ($this->calledFromTestCase()) {
                    if ($this->isMethodOfMockery($name)) {
                        return $this->mockeryBaseMock->$name(...$arguments);
                    }
                    return new MethodCall(
                        $this->mockeryBaseMock,
                        $name,
                        $arguments
                    );
                } else {
                    return $this->mockeryBaseMock->$name(...$arguments);
                }
            }

            public function __get(string $name)
            {
                return $this->mockeryBaseMock->$name;
            }

            public function __set(string $name, $value): void
            {
                $this->mockeryBaseMock->$name = $value;
            }

            private function calledFromTestCase(): bool
            {
                $debugBacktrace = debug_backtrace(limit: 4);
                return is_a($debugBacktrace[2]['class'] ?? '', TestCase::class, true);
            }

            private function isMethodOfMockery(string $method): bool
            {
                return method_exists(MockInterface::class, $method)
                    || method_exists(LegacyMockInterface::class, $method);
            }
        };
    }
}
