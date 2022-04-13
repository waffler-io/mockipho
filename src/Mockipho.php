<?php

declare(strict_types = 1);

/*
 * This file is part of Waffler\Mockipho.
 * (c) Erick Johnson Almeida de Menezes <erickmenezes.dev@gmail.com>
 * This source file is subject to the MIT licence that is bundled
 * with this source code in the file LICENCE.
 */

namespace Waffler\Mockipho;

use Waffler\Mockipho\Expectations\AnyArray;
use Waffler\Mockipho\Expectations\AnyBoolean;
use Waffler\Mockipho\Expectations\AnyDouble;
use Waffler\Mockipho\Expectations\AnyFloat;
use Waffler\Mockipho\Expectations\AnyInstanceOf;
use Waffler\Mockipho\Expectations\AnyInt;
use Waffler\Mockipho\Expectations\AnyObject;
use Waffler\Mockipho\Expectations\AnyOf;
use Waffler\Mockipho\Expectations\AnyResource;
use Waffler\Mockipho\Expectations\AnyString;
use Waffler\Mockipho\Expectations\AnyValue;
use Waffler\Mockipho\Expectations\TypeExpectation;

/**
 * Class Mockipho.
 *
 * @author ErickJMenezes <erickmenezes.dev@gmail.com>
 */
class Mockipho
{
    /**
     * Defines an expectation for a method call.
     *
     * @param \Closure $methodIsCalled
     * @param mixed    ...$withArgs
     *
     * @return \Waffler\Mockipho\ExpectationBuilder
     * @throws \ReflectionException
     * @author         ErickJMenezes <erickmenezes.dev@gmail.com>
     * @psalm-suppress DuplicateFunction
     */
    public static function when(MethodCall $methodCall): ExpectationBuilder
    {
        $expectation = new ExpectationBuilder($methodCall->mock->shouldReceive($methodCall->method));
        if (empty($methodCall->arguments)) {
            return $expectation;
        }
        return $expectation->withArgs(function (mixed ...$args) use ($methodCall) {
            $valid = true;
            foreach ($methodCall->arguments as $index => $expectedArg) {
                if (!$valid) {
                    break;
                } elseif ($expectedArg instanceof TypeExpectation) {
                    $valid = $expectedArg->test($args[$index]);
                } else {
                    $valid = $args[$index] === $expectedArg;
                }
            }
            return $valid;
        });
    }

    public static function anyString(): TypeExpectation
    {
        return new AnyString();
    }

    public static function anyInt(): TypeExpectation
    {
        return new AnyInt();
    }

    public static function anyValue(): TypeExpectation
    {
        return new AnyValue();
    }

    public static function anyOf(array $possibilities): TypeExpectation
    {
        return new AnyOf($possibilities);
    }

    public static function anyBoolean(): TypeExpectation
    {
        return new AnyBoolean();
    }

    public static function anyFloat(): TypeExpectation
    {
        return new AnyFloat();
    }

    public static function anyDouble(): TypeExpectation
    {
        return new AnyDouble();
    }

    public static function anyResource(): TypeExpectation
    {
        return new AnyResource();
    }

    public static function anyObject(): TypeExpectation
    {
        return new AnyObject();
    }

    /**
     * @param string $classString
     * @psalm-param class-string $classString
     *
     * @return \Waffler\Mockipho\Expectations\TypeExpectation
     * @author ErickJMenezes <erickmenezes.dev@gmail.com>
     */
    public static function anyInstanceOf(string $classString): TypeExpectation
    {
        return new AnyInstanceOf($classString);
    }

    public static function anyArray(): TypeExpectation
    {
        return new AnyArray();
    }
}
