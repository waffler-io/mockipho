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
use Waffler\Mockipho\Mock;
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
        $this->mocks = new WeakMap();
    }

    public function load(object $object): void
    {
        $reflection = new ReflectionClass($object);
        $properties = $reflection->getProperties();
        foreach ($properties as $property) {
            if (!empty($property->getAttributes(Mock::class))) {
                $mock = $this->resolveOrCreateMock($property->getType()->getName());
                $property->setValue($object, $mock);
            }
        }
    }

    private function resolveOrCreateMock(string $className): MockInterface
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
