<?php

declare(strict_types=1);

/*
 * This file is part of Waffler\Mockipho.
 * (c) Erick Johnson Almeida de Menezes <erickmenezes.dev@gmail.com>
 * This source file is subject to the MIT licence that is bundled
 * with this source code in the file LICENCE.
 */

namespace Waffler\Mockipho;

use InvalidArgumentException;
use Waffler\Mockipho\Matchers\AnyArray;
use Waffler\Mockipho\Matchers\AnyBoolean;
use Waffler\Mockipho\Matchers\AnyCallable;
use Waffler\Mockipho\Matchers\AnyDouble;
use Waffler\Mockipho\Matchers\AnyFloat;
use Waffler\Mockipho\Matchers\AnyInstanceOf;
use Waffler\Mockipho\Matchers\AnyInt;
use Waffler\Mockipho\Matchers\AnyObject;
use Waffler\Mockipho\Matchers\AnyOf;
use Waffler\Mockipho\Matchers\AnyResource;
use Waffler\Mockipho\Matchers\AnyString;
use Waffler\Mockipho\Matchers\AnyValue;
use Waffler\Mockipho\Matchers\Matcher;

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
     * @param mixed $methodCall
     *
     * @return \Waffler\Mockipho\ExpectationBuilder
     * @author         ErickJMenezes <erickmenezes.dev@gmail.com>
     * @psalm-suppress DuplicateFunction
     */
    public static function when(mixed $methodCall): ExpectationBuilder
    {
        if (!$methodCall instanceof MethodCall) {
            throw new InvalidArgumentException("The argument must be the method call of a mock.");
        }

        $expectation = new ExpectationBuilder($methodCall->mock->shouldReceive($methodCall->method));
        if (empty($methodCall->arguments)) {
            return $expectation;
        }
        return $expectation->withArgs(function (mixed ...$args) use (&$methodCall) {
            $valid = true;
            foreach ($methodCall->arguments as $index => $expectedArg) {
                if (!$valid) {
                    break;
                } elseif ($expectedArg instanceof Matcher || $expectedArg instanceof \Hamcrest\Matcher) {
                    $valid = $expectedArg->matches($args[$index]);
                } else {
                    $valid = $args[$index] === $expectedArg;
                }
            }
            return $valid;
        });
    }

    public static function anyString(): Matcher
    {
        return new AnyString();
    }

    public static function anyInt(): Matcher
    {
        return new AnyInt();
    }

    public static function anyValue(): Matcher
    {
        return new AnyValue();
    }

    public static function anyOf(array $possibilities): Matcher
    {
        return new AnyOf($possibilities);
    }

    public static function anyBoolean(): Matcher
    {
        return new AnyBoolean();
    }

    public static function anyFloat(): Matcher
    {
        return new AnyFloat();
    }

    public static function anyDouble(): Matcher
    {
        return new AnyDouble();
    }

    public static function anyResource(): Matcher
    {
        return new AnyResource();
    }

    public static function anyObject(): Matcher
    {
        return new AnyObject();
    }

    /**
     * @param string $classString
     *
     * @psalm-param class-string $classString
     *
     * @return \Waffler\Mockipho\Matchers\Matcher
     * @author ErickJMenezes <erickmenezes.dev@gmail.com>
     */
    public static function anyInstanceOf(string $classString): Matcher
    {
        return new AnyInstanceOf($classString);
    }

    public static function anyArray(): Matcher
    {
        return new AnyArray();
    }

    public static function anyCallable(): Matcher
    {
        return new AnyCallable();
    }
}
