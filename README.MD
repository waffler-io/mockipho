# Mockipho

Mockipho is a wrapper around mockery that allows you to create mocks with the php brand new php 8 annotations. It also
allows the dev to use the provided functions to create expectations and verify the mocks. Check an example of usage
below:

```php
<?php

namespace Your\Namespace\Tests;

use Waffler\Mockipho\Mock;
use function Waffler\Mockipho\{when, anyString, anyInt};

class SomeTest extends \Waffler\Mockipho\TestCase
{
    #[Mock]
    private Foo $fooMock;

    public function testSomething()
    {
        when($this->fooMock->bar(anyString(), anyInt()))
            ->thenReturn('it works!');
            
        self::assertEquals('it works!', $this->fooMock->bar('foo', 1)); // [OK] passed.
    }
}
```
