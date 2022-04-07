<?php

/*
 * This file is part of Waffler\Mockipho.
 * (c) Erick Johnson Almeida de Menezes <erickmenezes.dev@gmail.com>
 * This source file is subject to the MIT licence that is bundled
 * with this source code in the file LICENCE.
 */

namespace Waffler\Mockipho\Exceptions;

use ReflectionProperty;
use RuntimeException;

/**
 * Class IllegalPropertyException.
 *
 * @author ErickJMenezes <erickmenezes.dev@gmail.com>
 */
class IllegalPropertyException extends RuntimeException
{
    public function __construct(
        public readonly ReflectionProperty $property,
        public readonly string $reason = '',
    ) {
        parent::__construct("The property {$property->getName()} is not illegal. $reason");
    }
}
