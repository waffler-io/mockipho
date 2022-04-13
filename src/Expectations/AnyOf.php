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
 * Class AnyOf.
 *
 * @author ErickJMenezes <erickmenezes.dev@gmail.com>
 */
class AnyOf implements TypeExpectation
{
    public function __construct(
        private array $possibilities,
    ) {}

    public function test(mixed $value): bool
    {
        foreach ($this->possibilities as $possibility) {
            if ($possibility instanceof TypeExpectation && $possibility->test($value)) {
                return true;
            } elseif ($possibility === $value) {
                return true;
            }
        }
        return false;
    }
}
