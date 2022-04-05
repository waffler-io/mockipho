<?php

declare(strict_types = 1);

/*
 * This file is part of Waffler\Mockipho.
 * (c) Erick Johnson Almeida de Menezes <erickmenezes.dev@gmail.com>
 * This source file is subject to the MIT licence that is bundled
 * with this source code in the file LICENCE.
 */

namespace Waffler\Mockipho\Expectations;

/**
 * Class AnyInstanceOf.
 *
 * @author ErickJMenezes <erickmenezes.dev@gmail.com>
 */
class AnyInstanceOf implements TypeExpectation
{
    /**
     * @param string $classString
     * @psalm-param class-string $classString
     */
    public function __construct(
        public readonly string $classString,
    ) {}

    public function test(mixed $value): bool
    {
        return $value instanceof $this->classString;
    }
}
