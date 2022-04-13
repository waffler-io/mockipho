<?php

namespace Waffler\Mockipho;

use Mockery\LegacyMockInterface;
use Mockery\MockInterface;

/**
 * Class MethodCall.
 *
 * @author ErickJMenezes <erickmenezes.dev@gmail.com>
 */
class MethodCall
{
    public function __construct(
        public MockInterface|LegacyMockInterface $mock,
        public string $method,
        public array $arguments = [],
    ) {}
}
