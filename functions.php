<?php

declare(strict_types=1);

/*
 * This file is part of Waffler\Mockipho.
 * (c) Erick Johnson Almeida de Menezes <erickmenezes.dev@gmail.com>
 * This source file is subject to the MIT licence that is bundled
 * with this source code in the file LICENCE.
 */

namespace Waffler\Mockipho;

use Waffler\Mockipho\Matchers\Matcher;

/**
 * Defines an expectation for a method call.
 *
 * @param mixed $methodCall
 *
 * @return \Waffler\Mockipho\ExpectationBuilder
 * @psalm-suppress DuplicateFunction
 * @author         ErickJMenezes <erickmenezes.dev@gmail.com>
 */
function when(mixed $methodCall): ExpectationBuilder
{
    return Mockipho::when($methodCall);
}

/**
 * Expects the given parameter to be an array.
 *
 * @return \Waffler\Mockipho\Matchers\Matcher
 * @author ErickJMenezes <erickmenezes.dev@gmail.com>
 */
function anyArray(): Matcher
{
    return Mockipho::anyArray();
}

/**
 * Expects the given parameter to be an boolean.
 *
 * @return \Waffler\Mockipho\Matchers\Matcher
 * @author ErickJMenezes <erickmenezes.dev@gmail.com>
 */
function anyBoolean(): Matcher
{
    return Mockipho::anyBoolean();
}

/**
 * Expects the given parameter to be a double.
 *
 * @return \Waffler\Mockipho\Matchers\Matcher
 * @author ErickJMenezes <erickmenezes.dev@gmail.com>
 */
function anyDouble(): Matcher
{
    return Mockipho::anyDouble();
}

/**
 * Expects the given parameter to be a float.
 *
 * @return \Waffler\Mockipho\Matchers\Matcher
 * @author ErickJMenezes <erickmenezes.dev@gmail.com>
 */
function anyFloat(): Matcher
{
    return Mockipho::anyFloat();
}

/**
 * Expects the given parameter must be an instance of the given className.
 *
 * @param string             $classString
 *
 * @psalm-param class-string $classString
 *
 * @return \Waffler\Mockipho\Matchers\Matcher
 * @author ErickJMenezes <erickmenezes.dev@gmail.com>
 */
function anyInstanceOf(string $classString): Matcher
{
    return Mockipho::anyInstanceOf($classString);
}

/**
 * Expects the given parameter to be an integer.
 *
 * @return \Waffler\Mockipho\Matchers\Matcher
 * @author ErickJMenezes <erickmenezes.dev@gmail.com>
 */
function anyInt(): Matcher
{
    return Mockipho::anyInt();
}

/**
 * Expects the given parameter to be an object.
 *
 * @return \Waffler\Mockipho\Matchers\Matcher
 * @author ErickJMenezes <erickmenezes.dev@gmail.com>
 */
function anyObject(): Matcher
{
    return Mockipho::anyObject();
}

/**
 * Expects the argument to be any of the given values.
 *
 * @param array $possibilities
 *
 * @return \Waffler\Mockipho\Matchers\Matcher
 * @author ErickJMenezes <erickmenezes.dev@gmail.com>
 */
function anyOf(array $possibilities): Matcher
{
    return Mockipho::anyOf($possibilities);
}

/**
 * Expects the given parameter to be a resource.
 *
 * @return \Waffler\Mockipho\Matchers\Matcher
 * @author ErickJMenezes <erickmenezes.dev@gmail.com>
 */
function anyResource(): Matcher
{
    return Mockipho::anyResource();
}

/**
 * Expects the given parameter to be a string.
 *
 * @return \Waffler\Mockipho\Matchers\Matcher
 * @author ErickJMenezes <erickmenezes.dev@gmail.com>
 */
function anyString(): Matcher
{
    return Mockipho::anyString();
}

/**
 * Expects the given parameter to be any value.
 *
 * @return \Waffler\Mockipho\Matchers\Matcher
 * @author ErickJMenezes <erickmenezes.dev@gmail.com>
 */
function anyValue(): Matcher
{
    return Mockipho::anyValue();
}

/**
 * Expects the given parameter to be any callable.
 *
 * @return \Waffler\Mockipho\Matchers\Matcher
 * @author ErickJMenezes <erickmenezes.dev@gmail.com>
 */
function anyCallable(): Matcher
{
    return Mockipho::anyCallable();
}
