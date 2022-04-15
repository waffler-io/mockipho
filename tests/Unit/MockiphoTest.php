<?php

/*
 * This file is part of Waffler\Mockipho.
 * (c) Erick Johnson Almeida de Menezes <erickmenezes.dev@gmail.com>
 * This source file is subject to the MIT licence that is bundled
 * with this source code in the file LICENCE.
 */

namespace Waffler\Mockipho\Tests\Unit;

use Mockery\Exception\NoMatchingExpectationException;
use Mockery\ExpectationInterface;
use stdClass;
use Waffler\Mockipho\Expectations\TypeExpectation;
use Waffler\Mockipho\Mock;
use Waffler\Mockipho\Mockipho;
use Waffler\Mockipho\TestCase;
use Waffler\Mockipho\Tests\Fixtures\FakeServices\ServiceA;

/**
 * Class MockiphoTest.
 *
 * @author         ErickJMenezes <erickmenezes.dev@gmail.com>
 * @covers         \Waffler\Mockipho\Mockipho
 * @covers         \Waffler\Mockipho\Expectations\AnyArray
 * @covers         \Waffler\Mockipho\Expectations\AnyBoolean
 * @covers         \Waffler\Mockipho\Expectations\AnyDouble
 * @covers         \Waffler\Mockipho\Expectations\AnyFloat
 * @covers         \Waffler\Mockipho\Expectations\AnyInstanceOf
 * @covers         \Waffler\Mockipho\Expectations\AnyInt
 * @covers         \Waffler\Mockipho\Expectations\AnyObject
 * @covers         \Waffler\Mockipho\Expectations\AnyOf
 * @covers         \Waffler\Mockipho\Expectations\AnyResource
 * @covers         \Waffler\Mockipho\Expectations\AnyString
 * @covers         \Waffler\Mockipho\Expectations\AnyValue
 * @covers         \Waffler\Mockipho\ExpectationBuilder
 * @covers         \Waffler\Mockipho\MethodCall
 * @psalm-suppress PropertyNotSetInConstructor
 */
class MockiphoTest extends TestCase
{
    /**
     * @var \Waffler\Mockipho\Tests\Fixtures\FakeServices\ServiceA&\Mockery\MockInterface
     */
    #[Mock]
    private ServiceA $serviceA;

    /**
     * @var \Waffler\Mockipho\Expectations\TypeExpectation&\Mockery\MockInterface
     */
    #[Mock]
    private TypeExpectation $typeExpectation;

    /**
     * @return void
     * @throws \ReflectionException
     * @author ErickJMenezes <erickmenezes.dev@gmail.com>
     * @test
     */
    public function itMustCreateTheExpectationForTheGivenClosure(): void
    {
        $expectation = Mockipho::when($this->serviceA->getFoo())
            ->thenReturn('it works!');

        self::assertInstanceOf(ExpectationInterface::class, $expectation);
        $expectationDirector = $expectation->getMock()->mockery_getExpectationsFor('getFoo');
        self::assertNotNull($expectationDirector);
        self::assertEquals('it works!', $expectationDirector->call([]));
    }

    /**
     * @return void
     * @throws \ReflectionException
     * @author ErickJMenezes <erickmenezes.dev@gmail.com>
     * @test
     */
    public function itMustAddTheArgumentsExpectationsForTheMethod(): void
    {
        Mockipho::when($this->serviceA->sum(1, 2))
            ->thenReturn(5);

        self::assertEquals(5, $this->serviceA->mockery_getExpectationsFor('sum')
            ?->call([1, 2]));
    }

    /**
     * @return void
     * @throws \ReflectionException
     * @author ErickJMenezes <erickmenezes.dev@gmail.com>
     * @test
     */
    public function itMustRejectTheArgumentsWhenTheExpectedValueIsNotGiven(): void
    {
        self::expectException(NoMatchingExpectationException::class);
        Mockipho::when($this->serviceA->sum(Mockipho::anyString(), 2))
            ->thenReturn(5);
        self::assertEquals(5, $this->serviceA->mockery_getExpectationsFor('sum')
            ?->call([1, 2]));
    }

    /**
     * @return void
     * @author ErickJMenezes <erickmenezes.dev@gmail.com>
     * @test
     */
    public function itMustReturnFalseWhenTheArgumentIsNotAnArray(): void
    {
        $expectationMatcher = Mockipho::anyArray();
        self::assertFalse($expectationMatcher->test('foo'));
    }

    /**
     * @return void
     * @author ErickJMenezes <erickmenezes.dev@gmail.com>
     * @test
     */
    public function itMustReturnTrueWhenTheArgumentIsNotAnArray(): void
    {
        $expectationMatcher = Mockipho::anyArray();
        self::assertTrue($expectationMatcher->test([]));
    }

    /**
     * @return void
     * @author ErickJMenezes <erickmenezes.dev@gmail.com>
     * @test
     */
    public function itMustReturnFalseWhenTheArgumentIsNotBoolean(): void
    {
        $expectationMatcher = Mockipho::anyBoolean();
        self::assertFalse($expectationMatcher->test('foo'));
    }

    /**
     * @return void
     * @author ErickJMenezes <erickmenezes.dev@gmail.com>
     * @test
     */
    public function itMustReturnTrueWhenTheArgumentIsBoolean(): void
    {
        $expectationMatcher = Mockipho::anyBoolean();
        self::assertTrue($expectationMatcher->test(true));
    }

    /**
     * @return void
     * @author ErickJMenezes <erickmenezes.dev@gmail.com>
     * @test
     */
    public function itMustReturnFalseWhenTheArgumentIsNotDouble(): void
    {
        $expectationMatcher = Mockipho::anyDouble();
        self::assertFalse($expectationMatcher->test('foo'));
    }

    /**
     * @return void
     * @author ErickJMenezes <erickmenezes.dev@gmail.com>
     * @test
     */
    public function itMustReturnTrueWhenTheArgumentIsDouble(): void
    {
        $expectationMatcher = Mockipho::anyDouble();
        self::assertTrue($expectationMatcher->test(12.3345));
    }

    /**
     * @return void
     * @author ErickJMenezes <erickmenezes.dev@gmail.com>
     * @test
     */
    public function itMustReturnFalseWhenTheArgumentIsNotFloat(): void
    {
        $expectationMatcher = Mockipho::anyFloat();
        self::assertFalse($expectationMatcher->test('foo'));
    }

    /**
     * @return void
     * @author ErickJMenezes <erickmenezes.dev@gmail.com>
     * @test
     */
    public function itMustReturnTrueWhenTheArgumentIsFloat(): void
    {
        $expectationMatcher = Mockipho::anyFloat();
        self::assertTrue($expectationMatcher->test(12.3345));
    }

    /**
     * @return void
     * @author ErickJMenezes <erickmenezes.dev@gmail.com>
     * @test
     */
    public function itMustReturnFalseWhenTheArgumentIsNotInstanceOf(): void
    {
        $expectationMatcher = Mockipho::anyInstanceOf(ServiceA::class);
        self::assertFalse($expectationMatcher->test(1));
    }

    /**
     * @return void
     * @author ErickJMenezes <erickmenezes.dev@gmail.com>
     * @test
     */
    public function itMustReturnTrueWhenTheArgumentIsInstanceOf(): void
    {
        $expectationMatcher = Mockipho::anyInstanceOf(ServiceA::class);
        self::assertTrue($expectationMatcher->test(new ServiceA(1)));
    }

    /**
     * @return void
     * @author ErickJMenezes <erickmenezes.dev@gmail.com>
     * @test
     */
    public function itMustReturnFalseWhenTheArgumentIsNotInteger(): void
    {
        $expectationMatcher = Mockipho::anyInt();
        self::assertFalse($expectationMatcher->test(1.3));
    }

    /**
     * @return void
     * @author ErickJMenezes <erickmenezes.dev@gmail.com>
     * @test
     */
    public function itMustReturnTrueWhenTheArgumentIsInteger(): void
    {
        $expectationMatcher = Mockipho::anyInt();
        self::assertTrue($expectationMatcher->test(1));
    }

    /**
     * @return void
     * @author ErickJMenezes <erickmenezes.dev@gmail.com>
     * @test
     */
    public function itMustReturnFalseWhenTheArgumentIsNotObject(): void
    {
        $expectationMatcher = Mockipho::anyObject();
        self::assertFalse($expectationMatcher->test(1));
    }

    /**
     * @return void
     * @author ErickJMenezes <erickmenezes.dev@gmail.com>
     * @test
     */
    public function itMustReturnTrueWhenTheArgumentIsObject(): void
    {
        $expectationMatcher = Mockipho::anyObject();
        self::assertTrue($expectationMatcher->test(new stdClass()));
    }

    /**
     * @return void
     * @author ErickJMenezes <erickmenezes.dev@gmail.com>
     * @test
     */
    public function itMustReturnFalseWhenTheArgumentIsNotAnyOfValues(): void
    {
        $expectationMatcher = Mockipho::anyOf([1, 2]);
        self::assertFalse($expectationMatcher->test(3));
    }

    /**
     * @return void
     * @author ErickJMenezes <erickmenezes.dev@gmail.com>
     * @test
     */
    public function itMustReturnTrueWhenTheArgumentIsAnyOfValues(): void
    {
        $expectationMatcher = Mockipho::anyOf([1, 2]);
        self::assertTrue($expectationMatcher->test(1));
    }

    /**
     * @return void
     * @author ErickJMenezes <erickmenezes.dev@gmail.com>
     * @test
     */
    public function itMustReturnTrueWhenTheArgumentIsAnyOfTypeMatchers(): void
    {
        $expectationMatcher = Mockipho::anyOf([Mockipho::anyInt()]);
        self::assertTrue($expectationMatcher->test(1));
    }

    /**
     * @return void
     * @author ErickJMenezes <erickmenezes.dev@gmail.com>
     * @test
     */
    public function itMustReturnFalseWhenTheArgumentIsNotResource(): void
    {
        $expectationMatcher = Mockipho::anyResource();
        self::assertFalse($expectationMatcher->test(3));
    }

    /**
     * @return void
     * @author ErickJMenezes <erickmenezes.dev@gmail.com>
     * @test
     */
    public function itMustReturnTrueWhenTheArgumentIsResource(): void
    {
        $file = fopen(__FILE__, 'r');
        $expectationMatcher = Mockipho::anyResource();
        self::assertTrue($expectationMatcher->test($file));
        fclose($file);
    }

    /**
     * @return void
     * @author ErickJMenezes <erickmenezes.dev@gmail.com>
     * @test
     */
    public function itMustReturnFalseWhenTheArgumentIsNotString(): void
    {
        $expectationMatcher = Mockipho::anyString();
        self::assertFalse($expectationMatcher->test(1));
    }

    /**
     * @return void
     * @author ErickJMenezes <erickmenezes.dev@gmail.com>
     * @test
     */
    public function itMustReturnTrueWhenTheArgumentIsString(): void
    {
        $expectationMatcher = Mockipho::anyString();
        self::assertTrue($expectationMatcher->test('foo'));
    }

    /**
     * @return void
     * @author ErickJMenezes <erickmenezes.dev@gmail.com>
     * @test
     */
    public function itMustReturnTrueIfIsGivenAnyValue(): void
    {
        $expectationMatcher = Mockipho::anyValue();
        self::assertTrue($expectationMatcher->test(1));
    }

    /**
     * @return void
     * @author ErickJMenezes <erickmenezes.dev@gmail.com>
     * @test
     */
    public function itMustReturnTrueIfIsGivenAnyCallable(): void
    {
        $expectationMatcher = Mockipho::anyCallable();
        self::assertTrue($expectationMatcher->test(fn () => null));
    }

    /**
     * @return void
     * @throws \ReflectionException
     * @author ErickJMenezes <erickmenezes.dev@gmail.com>
     * @test
     */
    public function itMustMatchTheArgumentToTheTypeExpectation(): void
    {
        Mockipho::when($this->typeExpectation->test(Mockipho::anyValue()))
            ->thenReturn(true);

        self::assertTrue($this->typeExpectation->mockery_getExpectationsFor('test')
            ?->call(['foo']));
    }
}
