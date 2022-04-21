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
 * Class AnyInstanceOf.
 *
 * @author ErickJMenezes <erickmenezes.dev@gmail.com>
 */
class AnyInstanceOf implements Matcher
{
    /**
     * @param string $classString
     * @psalm-param class-string $classString
     */
    public function __construct(
        public string $classString,
    ) {
    }

    public function matches(mixed $value): bool
    {
        return $value instanceof $this->classString;
    }
}
