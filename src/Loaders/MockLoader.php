<?php

declare(strict_types = 1);

/*
 * This file is part of Waffler\Mockipho.
 * (c) Erick Johnson Almeida de Menezes <erickmenezes.dev@gmail.com>
 * This source file is subject to the MIT licence that is bundled
 * with this source code in the file LICENCE.
 */

namespace Waffler\Mockipho\Loaders;

use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionNamedType;
use Waffler\Mockipho\Exceptions\IllegalPropertyException;
use Waffler\Mockipho\Mock;
use Waffler\Mockipho\MockProxy;

/**
 * Class MockLoader.
 *
 * @author ErickJMenezes <erickmenezes.dev@gmail.com>
 */
class MockLoader
{
    /**
     * @throws \ReflectionException
     */
    public function load(object $target): void
    {
        foreach ($this->getProperties($target) as $property) {
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
            $mock = MockProxy::create($reflectionType->getName());
            $property->setValue($target, $mock);
        }
    }

    /**
     * @param object $object
     *
     * @return \ReflectionProperty[]
     * @author ErickJMenezes <erickmenezes.dev@gmail.com>
     */
    private function getProperties(object $object): array
    {
        return (new ReflectionClass($object))->getProperties();
    }
}
