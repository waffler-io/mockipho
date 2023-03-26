<?php

/*
 * This file is part of Waffler\Mockipho.
 * (c) Erick Johnson Almeida de Menezes <erickmenezes.dev@gmail.com>
 * This source file is subject to the MIT licence that is bundled
 * with this source code in the file LICENCE.
 */

namespace Waffler\Mockipho\Tests\Unit;

use Hamcrest\Matchers;
use InvalidArgumentException;
use Mockery\Exception\NoMatchingExpectationException;
use Mockery\ExpectationInterface;
use PHPUnit\Framework\TestCase;
use stdClass;
use Waffler\Mockipho\Matchers\Matcher;
use Waffler\Mockipho\Mock;
use Waffler\Mockipho\Mockipho;
use Waffler\Mockipho\Tests\Fixtures\FakeServices\FakeServiceInterface;
use Waffler\Mockipho\Traits\LoadsMocks;

/**
 * Class MockiphoTest.
 *
 * @author         ErickJMenezes <erickmenezes.dev@gmail.com>
 * @covers \Waffler\Mockipho\Mockipho
 * @covers \Waffler\Mockipho\Matchers\AnyArray
 * @covers \Waffler\Mockipho\Matchers\AnyBoolean
 * @covers \Waffler\Mockipho\Matchers\AnyDouble
 * @covers \Waffler\Mockipho\Matchers\AnyFloat
 * @covers \Waffler\Mockipho\Matchers\AnyInstanceOf
 * @covers \Waffler\Mockipho\Matchers\AnyInt
 * @covers \Waffler\Mockipho\Matchers\AnyObject
 * @covers \Waffler\Mockipho\Matchers\AnyOf
 * @covers \Waffler\Mockipho\Matchers\AnyResource
 * @covers \Waffler\Mockipho\Matchers\AnyString
 * @covers \Waffler\Mockipho\Matchers\AnyValue
 * @covers \Waffler\Mockipho\Matchers\AnyCallable
 * @covers \Waffler\Mockipho\MethodCall
 * @covers \Waffler\Mockipho\Loaders\MockLoader
 * @covers \Waffler\Mockipho\Mockipho
 * @covers \Waffler\Mockipho\Traits\LoadsMocks
 * @covers \Waffler\Mockipho\MockProxy
 * @psalm-suppress PropertyNotSetInConstructor
 */
class MockiphoTest extends TestCase
{
    use LoadsMocks;

    /**
     * @var \Waffler\Mockipho\Tests\Fixtures\FakeServices\FakeServiceInterface&\Mockery\MockInterface
     */
    #[Mock]
    private FakeServiceInterface $fakeService;

    /**
     * @var \Waffler\Mockipho\Matchers\Matcher&\Mockery\MockInterface
     */
    #[Mock]
    private Matcher $typeExpectation;

    /**
     * @return void
     * @throws \ReflectionException
     * @author ErickJMenezes <erickmenezes.dev@gmail.com>
     * @test
     */
    public function itMustCreateTheExpectationForTheGivenClosure(): void
    {
        $expectation = Mockipho::when($this->fakeService->getFoo())
            ->thenReturn('it works!');

        self::assertInstanceOf(ExpectationInterface::class, $expectation);
        $expectationDirector = $expectation->getMock()
            ->mockery_getExpectationsFor('getFoo');
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
        Mockipho::when($this->fakeService->sum(1, 2))
            ->thenReturn(5);

        self::assertEquals(5, $this->fakeService->mockery_getExpectationsFor('sum')
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
        Mockipho::when($this->fakeService->sum(Mockipho::anyString(), 2))
            ->thenReturn(5);
        self::assertEquals(5, $this->fakeService->mockery_getExpectationsFor('sum')
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
        self::assertFalse($expectationMatcher->matches('foo'));
    }

    /**
     * @return void
     * @author ErickJMenezes <erickmenezes.dev@gmail.com>
     * @test
     */
    public function itMustReturnTrueWhenTheArgumentIsNotAnArray(): void
    {
        $expectationMatcher = Mockipho::anyArray();
        self::assertTrue($expectationMatcher->matches([]));
    }

    /**
     * @return void
     * @author ErickJMenezes <erickmenezes.dev@gmail.com>
     * @test
     */
    public function itMustReturnFalseWhenTheArgumentIsNotBoolean(): void
    {
        $expectationMatcher = Mockipho::anyBoolean();
        self::assertFalse($expectationMatcher->matches('foo'));
    }

    /**
     * @return void
     * @author ErickJMenezes <erickmenezes.dev@gmail.com>
     * @test
     */
    public function itMustReturnTrueWhenTheArgumentIsBoolean(): void
    {
        $expectationMatcher = Mockipho::anyBoolean();
        self::assertTrue($expectationMatcher->matches(true));
    }

    /**
     * @return void
     * @author ErickJMenezes <erickmenezes.dev@gmail.com>
     * @test
     */
    public function itMustReturnFalseWhenTheArgumentIsNotDouble(): void
    {
        $expectationMatcher = Mockipho::anyDouble();
        self::assertFalse($expectationMatcher->matches('foo'));
    }

    /**
     * @return void
     * @author ErickJMenezes <erickmenezes.dev@gmail.com>
     * @test
     */
    public function itMustReturnTrueWhenTheArgumentIsDouble(): void
    {
        $expectationMatcher = Mockipho::anyDouble();
        self::assertTrue($expectationMatcher->matches(12.3345));
    }

    /**
     * @return void
     * @author ErickJMenezes <erickmenezes.dev@gmail.com>
     * @test
     */
    public function itMustReturnFalseWhenTheArgumentIsNotFloat(): void
    {
        $expectationMatcher = Mockipho::anyFloat();
        self::assertFalse($expectationMatcher->matches('foo'));
    }

    /**
     * @return void
     * @author ErickJMenezes <erickmenezes.dev@gmail.com>
     * @test
     */
    public function itMustReturnTrueWhenTheArgumentIsFloat(): void
    {
        $expectationMatcher = Mockipho::anyFloat();
        self::assertTrue($expectationMatcher->matches(12.3345));
    }

    /**
     * @return void
     * @author ErickJMenezes <erickmenezes.dev@gmail.com>
     * @test
     */
    public function itMustReturnFalseWhenTheArgumentIsNotInstanceOf(): void
    {
        $expectationMatcher = Mockipho::anyInstanceOf(ServiceA::class);
        self::assertFalse($expectationMatcher->matches(1));
    }

    /**
     * @return void
     * @author ErickJMenezes <erickmenezes.dev@gmail.com>
     * @test
     */
    public function itMustReturnTrueWhenTheArgumentIsInstanceOf(): void
    {
        $expectationMatcher = Mockipho::anyInstanceOf(FakeServiceInterface::class);
        self::assertTrue($expectationMatcher->matches($this->fakeService));
    }

    /**
     * @return void
     * @author ErickJMenezes <erickmenezes.dev@gmail.com>
     * @test
     */
    public function itMustReturnFalseWhenTheArgumentIsNotInteger(): void
    {
        $expectationMatcher = Mockipho::anyInt();
        self::assertFalse($expectationMatcher->matches(1.3));
    }

    /**
     * @return void
     * @author ErickJMenezes <erickmenezes.dev@gmail.com>
     * @test
     */
    public function itMustReturnTrueWhenTheArgumentIsInteger(): void
    {
        $expectationMatcher = Mockipho::anyInt();
        self::assertTrue($expectationMatcher->matches(1));
    }

    /**
     * @return void
     * @author ErickJMenezes <erickmenezes.dev@gmail.com>
     * @test
     */
    public function itMustReturnFalseWhenTheArgumentIsNotObject(): void
    {
        $expectationMatcher = Mockipho::anyObject();
        self::assertFalse($expectationMatcher->matches(1));
    }

    /**
     * @return void
     * @author ErickJMenezes <erickmenezes.dev@gmail.com>
     * @test
     */
    public function itMustReturnTrueWhenTheArgumentIsObject(): void
    {
        $expectationMatcher = Mockipho::anyObject();
        self::assertTrue($expectationMatcher->matches(new stdClass()));
    }

    /**
     * @return void
     * @author ErickJMenezes <erickmenezes.dev@gmail.com>
     * @test
     */
    public function itMustReturnFalseWhenTheArgumentIsNotAnyOfValues(): void
    {
        $expectationMatcher = Mockipho::anyOf([1, 2]);
        self::assertFalse($expectationMatcher->matches(3));
    }

    /**
     * @return void
     * @author ErickJMenezes <erickmenezes.dev@gmail.com>
     * @test
     */
    public function itMustReturnTrueWhenTheArgumentIsAnyOfValues(): void
    {
        $expectationMatcher = Mockipho::anyOf([1, 2]);
        self::assertTrue($expectationMatcher->matches(1));
    }

    /**
     * @return void
     * @author ErickJMenezes <erickmenezes.dev@gmail.com>
     * @test
     */
    public function itMustReturnTrueWhenTheArgumentIsAnyOfTypeMatchers(): void
    {
        $expectationMatcher = Mockipho::anyOf([Mockipho::anyInt()]);
        self::assertTrue($expectationMatcher->matches(1));
    }

    /**
     * @return void
     * @author ErickJMenezes <erickmenezes.dev@gmail.com>
     * @test
     */
    public function itMustReturnFalseWhenTheArgumentIsNotResource(): void
    {
        $expectationMatcher = Mockipho::anyResource();
        self::assertFalse($expectationMatcher->matches(3));
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
        self::assertTrue($expectationMatcher->matches($file));
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
        self::assertFalse($expectationMatcher->matches(1));
    }

    /**
     * @return void
     * @author ErickJMenezes <erickmenezes.dev@gmail.com>
     * @test
     */
    public function itMustReturnTrueWhenTheArgumentIsString(): void
    {
        $expectationMatcher = Mockipho::anyString();
        self::assertTrue($expectationMatcher->matches('foo'));
    }

    /**
     * @return void
     * @author ErickJMenezes <erickmenezes.dev@gmail.com>
     * @test
     */
    public function itMustReturnTrueIfIsGivenAnyValue(): void
    {
        $expectationMatcher = Mockipho::anyValue();
        self::assertTrue($expectationMatcher->matches(1));
    }

    /**
     * @return void
     * @author ErickJMenezes <erickmenezes.dev@gmail.com>
     * @test
     */
    public function itMustReturnTrueIfIsGivenAnyCallable(): void
    {
        $expectationMatcher = Mockipho::anyCallable();
        self::assertTrue($expectationMatcher->matches(fn () => null));
    }

    /**
     * @return void
     * @throws \ReflectionException
     * @author ErickJMenezes <erickmenezes.dev@gmail.com>
     * @test
     */
    public function itMustMatchTheArgumentToTheTypeExpectation(): void
    {
        Mockipho::when($this->typeExpectation->matches(Mockipho::anyValue()))
            ->thenReturn(true);

        self::assertTrue($this->typeExpectation->mockery_getExpectationsFor('matches')
            ->call(['foo']));
    }

    /**
     * @return void
     * @author ErickJMenezes <erickmenezes.dev@gmail.com>
     * @test
     */
    public function itMustAcceptTheHamcrestMatcher(): void
    {
        Mockipho::when($this->typeExpectation->matches(Matchers::stringValue()))
            ->thenReturn(true);

        self::assertTrue($this->typeExpectation->mockery_getExpectationsFor('matches')
            ?->call(['foo']));
    }

    /**
     * @return void
     * @author ErickJMenezes <erickmenezes.dev@gmail.com>
     * @test
     */
    public function itMustRejectNonMethodCallObjects(): void
    {
        $this->expectException(InvalidArgumentException::class);
        Mockipho::when(true);
    }
}
