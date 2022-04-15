<?php

/*
 * This file is part of Waffler\Mockipho.
 * (c) Erick Johnson Almeida de Menezes <erickmenezes.dev@gmail.com>
 * This source file is subject to the MIT licence that is bundled
 * with this source code in the file LICENCE.
 */

namespace Waffler\Mockipho\Tests\Fixtures\FakeServices;

final class ServiceA
{
    public function __construct(
        private int $foo,
    ) {
    }

    public function getFoo(): string
    {
        return 'foo';
    }

    public function sum(int $a, int $b): int
    {
        return $a + $b;
    }

    public function dummy(mixed $value): mixed
    {
        return $value;
    }

    public function multipleArgumentsDummy(mixed $arg1, mixed $arg2, mixed $arg3): array
    {
        return [$arg1, $arg2, $arg3];
    }
}
