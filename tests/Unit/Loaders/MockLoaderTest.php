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
use Waffler\Mockipho\Exceptions\IllegalPropertyException;
use Waffler\Mockipho\Loaders\MockLoader;
use Waffler\Mockipho\Tests\Fixtures\FakeServices\FakeServiceInterface;
use Waffler\Mockipho\Tests\Fixtures\FakeTestCases\DummyClassWithClassAsMock;
use Waffler\Mockipho\Tests\Fixtures\FakeTestCases\DummyClassWithIllegalMockProperty;
use Waffler\Mockipho\Tests\Fixtures\FakeTestCases\DummyClassWithIllegalMockPropertyType;
use Waffler\Mockipho\Tests\Fixtures\FakeTestCases\DummyClassWithMockProperty;

use function Waffler\Mockipho\when;

/**
 * Class MockLoaderTest.
 *
 * @author ErickJMenezes <erickmenezes.dev@gmail.com>
 * @covers \Waffler\Mockipho\Loaders\MockLoader
 * @covers \Waffler\Mockipho\Exceptions\IllegalPropertyException
 * @covers \Waffler\Mockipho\MethodCall
 * @covers \Waffler\Mockipho\Mockipho
 * @covers \Waffler\Mockipho\MockProxy
 */
class MockLoaderTest extends TestCase
{
    /**
     * @return void
     * @throws \ReflectionException
     * @author ErickJMenezes <erickmenezes.dev@gmail.com>
     * @test
     */
    public function itMustCreateAMockForThePropertyWithTheMockAttribute(): void
    {
        $object = new DummyClassWithMockProperty();
        (new MockLoader())->load($object);
        self::assertTrue(isset($object->fakeService), "The object is not set.");
        self::assertInstanceOf(FakeServiceInterface::class, $object->fakeService);
        self::assertInstanceOf(MockInterface::class, $object->fakeService);
    }

    /**
     * @return void
     * @throws \ReflectionException
     * @author ErickJMenezes <erickmenezes.dev@gmail.com>
     * @test
     */
    public function itMustForwardTheCallToTheUnderlyingMockWhenTheCallIsFromTheTestCase(): void
    {
        $object = new DummyClassWithMockProperty();
        (new MockLoader())->load($object);
        when($object->fakeService->getFoo())->thenReturn('bar');
        self::assertNotEmpty($object->fakeService->mockery_getExpectationsFor('getFoo')->findExpectation([]));
    }

    /**
     * @return void
     * @throws \ReflectionException
     * @author ErickJMenezes <erickmenezes.dev@gmail.com>
     * @test
     */
    public function itMustThrowIllegalPropertyExceptionIfTheMockHasNoTypeAnnotation(): void
    {
        $this->expectException(IllegalPropertyException::class);
        $this->expectExceptionMessage("Mock property must have a type.");
        $object = new DummyClassWithIllegalMockProperty();
        (new MockLoader())->load($object);
    }

    /**
     * @return void
     * @throws \ReflectionException
     * @author ErickJMenezes <erickmenezes.dev@gmail.com>
     * @test
     */
    public function itMustThrowIllegalPropertyExceptionIfTheMockTypeIsNotClassOrInterface(): void
    {
        $this->expectException(IllegalPropertyException::class);
        $this->expectExceptionMessage("[string] is not a valid class or interface.");
        $object = new DummyClassWithIllegalMockPropertyType();
        (new MockLoader())->load($object);
    }

    /**
     * @return void
     * @throws \ReflectionException
     * @author ErickJMenezes <erickmenezes.dev@gmail.com>
     * @test
     */
    public function itMustThrowRuntimeExceptionWhenTheMockIsClass(): void
    {
        $this->expectException(\RuntimeException::class);
        (new MockLoader())->load(new DummyClassWithClassAsMock());
    }
}
