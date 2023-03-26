<?php

namespace Waffler\Mockipho\Traits;

use Waffler\Mockipho\Loaders\MockLoader;

trait LoadsMocks
{
    /**
     * Loads the mocks in the current test case.
     *
     * @return void
     * @throws \ReflectionException
     * @author ErickJMenezes <erickmenezes.dev@gmail.com>
     * @before
     */
    protected function setUpMockipho(): void
    {
        (new MockLoader())->load($this);
    }
}
