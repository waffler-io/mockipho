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
use ReflectionNamedType;
use Waffler\Mockipho\Exceptions\IllegalPropertyException;
use Waffler\Mockipho\Mock;
use ZEngine\Reflection\ReflectionClass;

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
            $mock = $this->createMockFor($reflectionType->getName());
            $property->setValue($object, $mock);
        }
    }

    /**
     * @param string $target
     *
     * @return object
     * @throws \ReflectionException
     * @author ErickJMenezes <erickmenezes.dev@gmail.com>
     */
    private function createMockFor(string $target): object
    {
        $targetReflection = new ReflectionClass($target);
        $baseMock = Mockery::mock(
            $targetReflection->isFinal()
                ? $targetReflection->newInstanceWithoutConstructor()
                : $target
        );
        $targetReflection->setFinal(false);
        $mock = $this->createMockProxy($baseMock, $targetReflection->isInterface() ? null : $target);
        $mockProxyReflection = new ReflectionClass($mock);

        if ($targetReflection->isInterface()) {
            $mockProxyReflection->addInterfaces($target);
        } else {
            foreach ($mockProxyReflection->getMethods(\ReflectionMethod::IS_PUBLIC) as $reflectionMethod) {
                if (in_array($reflectionMethod->getName(), ['__call', '__construct'], true)) {
                    continue;
                }
                $mockProxyReflection->removeMethods($reflectionMethod->getName());
            }
        }
        $mockProxyReflection->addInterfaces(MockInterface::class, LegacyMockInterface::class);
        return $mock;
    }

    private function createMockProxy(MockInterface|HigherOrderMessage|LegacyMockInterface $mock, ?string $className = null): object
    {
        $factory = eval(sprintf('return fn() => new class %s {
            public $mockeryBaseMock;
            
            public function __construct() {}

            public function __call(string $name, array $arguments): mixed
            {
                if (!$this->calledFromTestCase() || $this->isMethodOfMockery($name)) {
                    return $this->mockeryBaseMock->$name(...$arguments);
                }
                return new \Waffler\Mockipho\MethodCall(
                    $this->mockeryBaseMock,
                    $name,
                    $arguments
                );
            }

            private function calledFromTestCase(): bool
            {
                return is_a(debug_backtrace(limit: 3)[2]["class"] ?? "", \PHPUnit\Framework\TestCase::class, true);
            }

            private function isMethodOfMockery(string $method): bool
            {
                return method_exists(\Mockery\MockInterface::class, $method)
                    || method_exists(\Mockery\LegacyMockInterface::class, $method);
            }
        };', $className ? "extends \\$className" : ''));
        $obj = $factory();
        $obj->mockeryBaseMock = $mock;
        return $obj;
    }
}
