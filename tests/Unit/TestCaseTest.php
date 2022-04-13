<?php

/*
 * This file is part of Waffler\Mockipho.
 * (c) Erick Johnson Almeida de Menezes <erickmenezes.dev@gmail.com>
 * This source file is subject to the MIT licence that is bundled
 * with this source code in the file LICENCE.
 */

namespace Waffler\Mockipho\Tests\Unit;

use Mockery\MockInterface;
use Waffler\Mockipho\Mock;
use Waffler\Mockipho\Mockipho;
use Waffler\Mockipho\TestCase;
use Waffler\Mockipho\Tests\Fixtures\FakeServices\ServiceA;

/**
 * Class MockiphoPhpUnitIntegrationTest.
 *
 * @author         ErickJMenezes <erickmenezes.dev@gmail.com>
 * @covers         \Waffler\Mockipho\TestCase
 * @psalm-suppress PropertyNotSetInConstructor
 */
class TestCaseTest extends TestCase
{
    /**
     * @var \Waffler\Mockipho\Tests\Fixtures\FakeServices\ServiceA&MockInterface
     */
    #[Mock]
    private ServiceA $serviceA;

    /**
     * @throws \ReflectionException
     */
    public function testItShouldMockTheServiceA(): void
    {
        self::assertInstanceOf(MockInterface::class, $this->serviceA);

        Mockipho::when($this->serviceA->getFoo())
            ->twice()
            ->thenReturn('a', 'b');

        $expectationDirector = $this->serviceA->mockery_getExpectationsFor('getFoo');

        self::assertNotNull($expectationDirector);
        self::assertEquals('a', $expectationDirector->call([]));
        self::assertEquals('b', $expectationDirector->call([]));
    }
}
