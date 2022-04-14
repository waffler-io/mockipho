<?php

declare(strict_types=1);

/*
 * This file is part of Waffler\Mockipho.
 * (c) Erick Johnson Almeida de Menezes <erickmenezes.dev@gmail.com>
 * This source file is subject to the MIT licence that is bundled
 * with this source code in the file LICENCE.
 */

namespace Waffler\Mockipho\Expectations;

/**
 * Class AnyValue.
 *
 * @author ErickJMenezes <erickmenezes.dev@gmail.com>
 */
class AnyValue implements TypeExpectation
{
    public function test(mixed $value): bool
    {
        return true;
    }
}
