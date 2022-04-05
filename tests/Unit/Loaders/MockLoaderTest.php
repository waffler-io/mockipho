<?php

/*
 * This file is part of Waffler\Mockipho.
 * (c) Erick Johnson Almeida de Menezes <erickmenezes.dev@gmail.com>
 * This source file is subject to the MIT licence that is bundled
 * with this source code in the file LICENCE.
 */

namespace Waffler\Mockipho\Tests\Unit\Loaders;

use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use Waffler\Mockipho\Loaders\MockLoader;
use Waffler\Mockipho\Tests\Fixtures\FakeServices\ServiceA;
use Waffler\Mockipho\Tests\Fixtures\FakeTestCases\TestCaseA;

/**
 * Class MockLoaderTest.
 *
 * @author ErickJMenezes <erickmenezes.dev@gmail.com>
 * @covers \Waffler\Mockipho\Loaders\MockLoader
 */
class MockLoaderTest extends TestCase
{
    private MockLoader $mockLoader;

    protected function setUp(): void
    {
        parent::setUp();
        $this->mockLoader = new MockLoader();
    }

    /**
     * @return void
     * @author ErickJMenezes <erickmenezes.dev@gmail.com>
     * @test
     */
    public function itMustCreateAMockForThePropertyWithTheMockAttribute(): void
    {
        $object = new TestCaseA();
        $this->mockLoader->load($object);
        self::assertTrue(isset($object->serviceA), "The object is not set.");
        self::assertInstanceOf(ServiceA::class, $object->serviceA);
        self::assertInstanceOf(MockInterface::class, $object->serviceA);
    }

    /**
     * @return void
     * @author ErickJMenezes <erickmenezes.dev@gmail.com>
     * @test
     */
    public function itMustGetTheMockFromTheCacheIfTheMockIsAlreadyGenerated(): void
    {
        $objectA = new TestCaseA();
        $this->mockLoader->load($objectA);
        $objectB = new TestCaseA();
        $this->mockLoader->load($objectB);
        self::assertTrue(isset($objectA->serviceA), "The objectA mock is not set.");
        self::assertTrue(isset($objectB->serviceA), "The objectB mock is not set.");
        self::assertSame($objectA->serviceA, $objectB->serviceA);
    }
}
