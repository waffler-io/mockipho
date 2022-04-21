<?php

/*
 * This file is part of Waffler\Mockipho.
 * (c) Erick Johnson Almeida de Menezes <erickmenezes.dev@gmail.com>
 * This source file is subject to the MIT licence that is bundled
 * with this source code in the file LICENCE.
 */

namespace Waffler\Mockipho\Tests\Fixtures\FakeTestCases;

use Waffler\Mockipho\Mock;

class DummyClassWithClassAsMock
{
    #[Mock]
    public DummyClassWithMockProperty $dummyClassWithMockProperty;
}
