<?php

/*
 * This file is part of Waffler\Mockipho.
 * (c) Erick Johnson Almeida de Menezes <erickmenezes.dev@gmail.com>
 * This source file is subject to the MIT licence that is bundled
 * with this source code in the file LICENCE.
 */

namespace Waffler\Mockipho;

use Mockery\HigherOrderMessage;
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
        public MockInterface|LegacyMockInterface|HigherOrderMessage $mock,
        public string $method,
        public array $arguments = [],
    ) {
    }
}
