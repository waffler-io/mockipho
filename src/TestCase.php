<?php

declare(strict_types=1);

/*
 * This file is part of Waffler\Mockipho.
 * (c) Erick Johnson Almeida de Menezes <erickmenezes.dev@gmail.com>
 * This source file is subject to the MIT licence that is bundled
 * with this source code in the file LICENCE.
 */

namespace Waffler\Mockipho;

use Mockery\Adapter\Phpunit\MockeryTestCase;
use Waffler\Mockipho\Loaders\MockLoader;

/**
 * Class TestCase.
 *
 * @author ErickJMenezes <erickmenezes.dev@gmail.com>
 */
class TestCase extends MockeryTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->loadMockiphoMocks();
    }

    private function loadMockiphoMocks(): void
    {
        (new MockLoader())->load($this);
    }
}
