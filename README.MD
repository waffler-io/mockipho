# Mockipho

Mockipho is a wrapper around mockery that allows you to create mocks with brand new php 8 annotations, in a way similar
to Java Mockito. Take a look in the example below.

```php
<?php

namespace Your\Namespace\Tests;

use Your\Namespace\SomeClass;
use Waffler\Mockipho\Mock;
use function Waffler\Mockipho\{when, anyString, anyInt};

class SomeTest extends \Waffler\Mockipho\TestCase
{
    #[Mock]
    private Foo $fooMock;
    
    // No #[InjectMocks] yet...
    private SomeClass $someClass;
    
    protected function setUp() : void
    {
        parent::setUp();
        $this->someClass = new SomeClass($this->fooMock);
    }

    public function testSomething(): void
    {
        // It doesn't matter the bar() method parameter types. Everything just works.
        when($this->fooMock->bar(anyString(), anyInt()))
            ->thenReturn('it works!');

        self::assertEquals('it works!', $this->someClass->getFooBar()); // [OK]
    }
}
```

If you want to use this package, pleas add the autoloader of this package to your
phpunit.xml file. This autoloader is located at `vendor/waffler/mockipho/autoload.php`.

This custom autoloader loads a special class that is required by this package.

## In phpunit.xml
Add the bootstrap file to your phpunit.xml file.
```xml
<phpunit bootstrap="./vendor/waffler/mockipho/autoload.php">
    ...
</phpunit>
```
