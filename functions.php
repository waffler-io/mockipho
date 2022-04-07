<?php

declare(strict_types = 1);

/*
 * This file is part of Waffler\Mockipho.
 * (c) Erick Johnson Almeida de Menezes <erickmenezes.dev@gmail.com>
 * This source file is subject to the MIT licence that is bundled
 * with this source code in the file LICENCE.
 */

namespace Waffler\Mockipho;

use Closure;
use Mockery\CompositeExpectation;
use Mockery\Expectation;
use Mockery\ExpectationInterface;
use Waffler\Mockipho\Expectations\TypeExpectation;

/**
 * Defines an expectation for a method call.
 *
 * @param \Closure $methodIsCalled
 * @param mixed    ...$withArgs
 *
 * @return \Mockery\Expectation|\Mockery\CompositeExpectation|\Mockery\ExpectationInterface
 * @throws \ReflectionException
 * @throws \InvalidArgumentException
 * @author         ErickJMenezes <erickmenezes.dev@gmail.com>
 * @psalm-suppress DuplicateFunction
 */
function when(Closure $methodIsCalled, mixed ...$withArgs): Expectation|CompositeExpectation|ExpectationInterface
{
    return Mockipho::when($methodIsCalled, ...$withArgs);
}

/**
 * Expects the given parameter to be an array.
 *
 * @return \Waffler\Mockipho\Expectations\TypeExpectation
 * @author ErickJMenezes <erickmenezes.dev@gmail.com>
 */
function anyArray(): TypeExpectation
{
    return Mockipho::anyArray();
}

/**
 * Expects the given parameter to be an boolean.
 *
 * @return \Waffler\Mockipho\Expectations\TypeExpectation
 * @author ErickJMenezes <erickmenezes.dev@gmail.com>
 */
function anyBoolean(): TypeExpectation
{
    return Mockipho::anyBoolean();
}

/**
 * Expects the given parameter to be a double.
 *
 * @return \Waffler\Mockipho\Expectations\TypeExpectation
 * @author ErickJMenezes <erickmenezes.dev@gmail.com>
 */
function anyDouble(): TypeExpectation
{
    return Mockipho::anyDouble();
}

/**
 * Expects the given parameter to be a float.
 *
 * @return \Waffler\Mockipho\Expectations\TypeExpectation
 * @author ErickJMenezes <erickmenezes.dev@gmail.com>
 */
function anyFloat(): TypeExpectation
{
    return Mockipho::anyFloat();
}

/**
 * Expects the given parameter must be an instance of the given className.
 *
 * @param string $classString
 * @psalm-param class-string $classString
 *
 * @return \Waffler\Mockipho\Expectations\TypeExpectation
 * @author ErickJMenezes <erickmenezes.dev@gmail.com>
 */
function anyInstanceOf(string $classString): TypeExpectation
{
    return Mockipho::anyInstanceOf($classString);
}

/**
 * Expects the given parameter to be an integer.
 *
 * @return \Waffler\Mockipho\Expectations\TypeExpectation
 * @author ErickJMenezes <erickmenezes.dev@gmail.com>
 */
function anyInt(): TypeExpectation
{
    return Mockipho::anyInt();
}

/**
 * Expects the given parameter to be an object.
 *
 * @return \Waffler\Mockipho\Expectations\TypeExpectation
 * @author ErickJMenezes <erickmenezes.dev@gmail.com>
 */
function anyObject(): TypeExpectation
{
    return Mockipho::anyObject();
}

/**
 * Expects the argument to be any of the given values.
 *
 * @param array $possibilities
 *
 * @return \Waffler\Mockipho\Expectations\TypeExpectation
 * @author ErickJMenezes <erickmenezes.dev@gmail.com>
 */
function anyOf(array $possibilities): TypeExpectation
{
    return Mockipho::anyOf($possibilities);
}

/**
 * Expects the given parameter to be a resource.
 *
 * @return \Waffler\Mockipho\Expectations\TypeExpectation
 * @author ErickJMenezes <erickmenezes.dev@gmail.com>
 */
function anyResource(): TypeExpectation
{
    return Mockipho::anyResource();
}

/**
 * Expects the given parameter to be a string.
 *
 * @return \Waffler\Mockipho\Expectations\TypeExpectation
 * @author ErickJMenezes <erickmenezes.dev@gmail.com>
 */
function anyString(): TypeExpectation
{
    return Mockipho::anyString();
}

/**
 * Expects the given parameter to be ay value.
 *
 * @return \Waffler\Mockipho\Expectations\TypeExpectation
 * @author ErickJMenezes <erickmenezes.dev@gmail.com>
 */
function anyValue(): TypeExpectation
{
    return Mockipho::anyValue();
}