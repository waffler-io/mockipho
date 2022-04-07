<?php

declare(strict_types = 1);

/*
 * This file is part of Waffler\Mockipho.
 * (c) Erick Johnson Almeida de Menezes <erickmenezes.dev@gmail.com>
 * This source file is subject to the MIT licence that is bundled
 * with this source code in the file LICENCE.
 */

namespace Waffler\Mockipho\Loaders;

use Mockery;
use Mockery\MockInterface;
use ReflectionClass;
use ReflectionNamedType;
use Waffler\Mockipho\Exceptions\IllegalPropertyException;
use Waffler\Mockipho\Mock;
use WeakMap;

/**
 * Class MockLoader.
 *
 * @author ErickJMenezes <erickmenezes.dev@gmail.com>
 */
class MockLoader
{
    /**
     * @var \WeakMap<MockInterface|\Mockery\LegacyMockInterface, class-string>
     */
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
            $reflectionType = $property->getType();
            if (!$reflectionType instanceof ReflectionNamedType) {
                throw new IllegalPropertyException($property,
                    'Mock property must have a type. Intersection and Union types are not supported.');
            }
            if (!class_exists($reflectionType->getName()) && !interface_exists($reflectionType->getName())) {
                throw new IllegalPropertyException($property,
                    "[{$reflectionType->getName()}] is not a valid class or interface.");
            }
            $mock = $this->resolveOrCreateMock($reflectionType->getName());
            $property->setValue($object, $mock);
        }
    }

    /**
     * @param string             $className
     *
     * @psalm-param class-string $className
     *
     * @return \Mockery\MockInterface|\Mockery\LegacyMockInterface
     * @author ErickJMenezes <erickmenezes.dev@gmail.com>
     */
    private function resolveOrCreateMock(string $className): MockInterface|Mockery\LegacyMockInterface
    {
        foreach ($this->mocks as $mock => $mockClass) {
            if ($mockClass === $className) {
                return $mock;
            }
        }
        $mock = Mockery::mock($className);
        $this->mocks[$mock] = $className;
        return $mock;
    }
}
