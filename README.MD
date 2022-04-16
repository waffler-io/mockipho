# Mockipho

> **NOTE** This is a proof of concept. It is not intended to be used in production.
> If you want to use it, please make sure you understand the risks.


Mockipho is a wrapper around mockery that allows you to create mock objects integrated
with phpunit.
It's very similar to Java Mockito so take a look in the example below.

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
    
    // This class uses the Foo mock object
    private SomeClass $someClass;
    
    protected function setUp() : void
    {
        parent::setUp();
        // Giving the mock to the class as a dependency injection
        $this->someClass = new SomeClass($this->fooMock);
    }

    public function testSomething(): void
    {
        // It doesn't matter the argument types of the bar() method. Your IDE should
        // warn you about invalid arguments types, so don't worry about it.
        when($this->fooMock->bar(anyString(), anyInt()))
            ->thenReturn('it works!');

        self::assertEquals('it works!', $this->someClass->getFooBar()); // [OK]
    }
}
```

If you want to use this package, pleas add the autoloader of this package to your
phpunit.xml file. This autoloader is located at `vendor/waffler/mockipho/autoload.php`.

This custom autoloader loads a special class that is required by this package.

## How to use this package

### 0 - Installing this package
This package is not available on composer. You need to install it manually.
The easies way is adding this repository as a vcs repository in your composer.json and
add the dependency in the `require-dev` section. After that, just run `composer update`
and the package should be installed.

```json
{
    "require-dev": {
        "waffler/mockipho": "dev-master"
    },
    "repositories": [
      {
        "type": "vcs",
        "url": "https://github.com/waffler-io/mockipho"
      }
    ]
}
```


### 1 - Configuring the autoloader in your phpunit.xml file

Use the custom bootstrap file provided by this package in order to use this package.
Add this line to your phpunit.xml file:

```xml
<phpunit bootstrap="./vendor/waffler/mockipho/autoload.php">
    ...
</phpunit>
```

### 2 - Using the package custom TestCase
First of all, you need to use the custom TestCase provided by this package.

```php
use Waffler\Mockipho\TestCase;

class MyClassTest extends TestCase // Use the custom TestCase 
{}
```

Use the `#[Mock]` annotation/attribute to mark the properties that you want to mock.
```php
#[Mock]
private Foo $foo;
```

### 3 - Creating expectations
In your test case methods, you can use the `when()` function to create expectations.

```php
use function Waffler\Mockipho\{when};

when($foo->bar())
    ->thenReturn('it works!');
```

If your method expect some parameters, you can pass the arguments to the method you are calling.

```php
when($foo->bar('hello', 42))
    ->thenReturn('it works!');
```

If you want to say that your method expect "any" value of a specific type 
then you can use the `any**()` functions.

```php
when($foo->bar(anyString(), 42)) // the first argument can be any string
    ->thenReturn('it works!');
```

All functions are available in the `Waffler\Mockipho` namespace, you can import them like this:

```php
use function Waffler\Mockipho\{anyString, anyInt};
```

Or you can use the static methods in the Mockipho class:

```php
use Waffler\Mockipho\Mockipho;

Mockipho::when($foo->bar(Mockipho::anyString(), Mockipho::anyInt()))
    ->thenReturn('it works!');
```

> **Important**: Don't worry about your class parameters types when using the `any**()` functions
> because this package will automatically convert them to the right type.

### 4 - The complete list of functions
```php
when();
anyArray();
anyBoolean();
anyDouble();
anyFloat();
anyInstanceOf();
anyInt();
anyObject();
anyOf();
anyResource();
anyString();
anyValue();
anyCallable();
```

## Developing this package
This repository provides a docker environment with the required php extensions. 
You can just run the following command to build the docker environment:

```shell
docker-compose build
```

After building the image, you will be able to run commands directly in the php installed in the container by running
the following command:
```shell
# Change the "--version" command with whatever you want to run.

# To use the php cli:
docker-compose run php --version

# To use the composer cli:
docker-compose run php composer --version
```

> **Note:** If you use PHPStorm or another IDE capable of integrate with docker, 
> I recommend you to attach the docker environment to the IDE to facilitate the development.
