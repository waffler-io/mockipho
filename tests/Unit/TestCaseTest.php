<?php

/*
 * This file is part of Waffler\Mockipho.
 * (c) Erick Johnson Almeida de Menezes <erickmenezes.dev@gmail.com>
 * This source file is subject to the MIT licence that is bundled
 * with this source code in the file LICENCE.
 */

namespace Waffler\Mockipho\Tests\Unit;

use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use Waffler\Mockipho\Mock;
use Waffler\Mockipho\Tests\Fixtures\FakeServices\FakeServiceInterface;
use Waffler\Mockipho\Traits\LoadsMocks;

/**
 * Class MockiphoPhpUnitIntegrationTest.
 *
 * @author         ErickJMenezes <erickmenezes.dev@gmail.com>
 * @covers \Waffler\Mockipho\Traits\LoadsMocks
 * @covers \Waffler\Mockipho\MockProxy
 * @covers \Waffler\Mockipho\Loaders\MockLoader
 * @psalm-suppress PropertyNotSetInConstructor
 */
class TestCaseTest extends TestCase
{
    use LoadsMocks;

    /**
     * @var \Waffler\Mockipho\Tests\Fixtures\FakeServices\FakeServiceInterface&MockInterface
     */
    #[Mock]
    private FakeServiceInterface $fakeService;

    public function testItMustLoadTheMockInTheProperty(): void
    {
        self::assertTrue(isset($this->fakeService), 'The mock is not set');
        self::assertInstanceOf(MockInterface::class, $this->fakeService);
        self::assertInstanceOf(FakeServiceInterface::class, $this->fakeService);
    }
}
