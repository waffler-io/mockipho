<?php

/*
 * This file is part of Waffler\Mockipho.
 * (c) Erick Johnson Almeida de Menezes <erickmenezes.dev@gmail.com>
 * This source file is subject to the MIT licence that is bundled
 * with this source code in the file LICENCE.
 */

namespace Waffler\Mockipho;

use Mockery\CompositeExpectation;
use Mockery\Expectation;
use Mockery\ExpectationInterface;
use Mockery\LegacyMockInterface;
use Mockery\MockInterface;
use Mockery\VerificationExpectation;

/**
 * Class ExpectationBuilder.
 *
 * @author ErickJMenezes <erickmenezes.dev@gmail.com>
 * @mixin Expectation
 * @mixin VerificationExpectation
 * @mixin CompositeExpectation
 * @codeCoverageIgnore
 */
class ExpectationBuilder implements ExpectationInterface
{
    public function __construct(
        private ExpectationInterface|CompositeExpectation|Expectation|VerificationExpectation $expectation
    ) {
    }

    public function getOrderNumber(): int|null
    {
        return $this->expectation->getOrderNumber();
    }

    public function getMock(): MockInterface|LegacyMockInterface
    {
        return $this->expectation->getMock();
    }

    public function thenReturn(mixed ...$value): self
    {
        return $this->andReturn(...$value);
    }

    public function andReturn(...$args): self
    {
        $this->expectation->andReturn(...$args);
        return $this;
    }

    public function thenReturns(): self
    {
        return $this->andReturns();
    }

    public function andReturns(): self
    {
        $this->expectation->andReturn();
        return $this;
    }

    public function __call(string $method, array $args): mixed
    {
        $returnValue = $this->expectation->$method(...$args);
        if ($returnValue instanceof ExpectationInterface) {
            return new self($returnValue);
        }
        return $returnValue;
    }
}
