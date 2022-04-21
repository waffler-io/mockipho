<?php

/*
 * This file is part of Waffler\Mockipho.
 * (c) Erick Johnson Almeida de Menezes <erickmenezes.dev@gmail.com>
 * This source file is subject to the MIT licence that is bundled
 * with this source code in the file LICENCE.
 */

namespace Waffler\Mockipho\Matchers;

/**
 * Class AnyCallable.
 *
 * @author ErickJMenezes <erickmenezes.dev@gmail.com>
 */
class AnyCallable implements Matcher
{
    public function matches(mixed $value): bool
    {
        return is_callable($value);
    }
}
