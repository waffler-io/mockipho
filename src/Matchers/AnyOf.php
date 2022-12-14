<?php

declare(strict_types=1);

/*
 * This file is part of Waffler\Mockipho.
 * (c) Erick Johnson Almeida de Menezes <erickmenezes.dev@gmail.com>
 * This source file is subject to the MIT licence that is bundled
 * with this source code in the file LICENCE.
 */

namespace Waffler\Mockipho\Matchers;

/**
 * Class AnyOf.
 *
 * @author ErickJMenezes <erickmenezes.dev@gmail.com>
 */
class AnyOf implements Matcher
{
    public function __construct(
        private array $possibilities,
    ) {
    }

    public function matches(mixed $value): bool
    {
        foreach ($this->possibilities as $possibility) {
            if ($this->isMatcher($possibility) && $possibility->matches($value)) {
                return true;
            } elseif ($possibility === $value) {
                return true;
            }
        }
        return false;
    }

    private function isMatcher(mixed $possibleMatcher): bool
    {
        return $possibleMatcher instanceof \Hamcrest\Matcher
            || $possibleMatcher instanceof Matcher;
    }
}
