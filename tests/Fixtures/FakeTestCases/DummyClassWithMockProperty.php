<?php

/*
 * This file is part of Waffler\Mockipho.
 * (c) Erick Johnson Almeida de Menezes <erickmenezes.dev@gmail.com>
 * This source file is subject to the MIT licence that is bundled
 * with this source code in the file LICENCE.
 */

namespace Waffler\Mockipho\Tests\Fixtures\FakeTestCases;

use Waffler\Mockipho\Mock;
use Waffler\Mockipho\Tests\Fixtures\FakeServices\FakeServiceInterface;

/**
 * Class TestCaseA.
 *
 * @author ErickJMenezes <erickmenezes.dev@gmail.com>
 */
class DummyClassWithMockProperty
{
    /**
     * @var \Waffler\Mockipho\Tests\Fixtures\FakeServices\FakeServiceInterface&\Mockery\MockInterface
     */
    #[Mock]
    public FakeServiceInterface $fakeService;

    public FakeServiceInterface $ignore;

    public function getFoo(): string
    {
        return $this->fakeService->getFoo();
    }
}
