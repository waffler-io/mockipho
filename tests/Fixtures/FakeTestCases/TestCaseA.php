<?php

/*
 * This file is part of Waffler\Mockipho.
 * (c) Erick Johnson Almeida de Menezes <erickmenezes.dev@gmail.com>
 * This source file is subject to the MIT licence that is bundled
 * with this source code in the file LICENCE.
 */

namespace Waffler\Mockipho\Tests\Fixtures\FakeTestCases;

use Waffler\Mockipho\Mock;
use Waffler\Mockipho\Tests\Fixtures\FakeServices\ServiceA;

/**
 * Class TestCaseA.
 *
 * @author ErickJMenezes <erickmenezes.dev@gmail.com>
 */
class TestCaseA
{
    #[Mock]
    public readonly ServiceA $serviceA;
}
