# Mockipho

> **NOTE** This is a proof of concept. It is not intended to be used in production.
> If you want to use it, please make sure you understand the risks.
> Currently, it is not possible to mock classes, only interfaces.


Mockipho is a wrapper around mockery that allows you to create mock objects integrated
with phpunit. It's very similar to Java Mockito so take a look in the example below.

```php
<?php

namespace Your\Namespace\Tests;

use Your\Namespace\SomeClass;
use Your\Namespace\FooInterface;
use Waffler\Mockipho\Mock;
use function Waffler\Mockipho\{when, anyString, anyInt};
use Waffler\Mockipho\Traits\LoadsMocks;

class SomeTest extends \Waffler\Mockipho\TestCase
{
    use LoadsMocks;

    #[Mock]
    private FooInterface $foo;

    public function testSomething(): void
    {
        when($this->foo->bar(anyString(), anyInt()))
            ->thenReturn('it works!');

        // give the mock to your the class that depends on it and test whatever you want.
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
First of all, you need to use the following trait in your TestCase class.

```php
use Waffler\Mockipho\Traits\LoadsMocks;
```

Use the `#[Mock]` Attribute to mark the properties that you want to mock. Make sure the property is typed with a single type.
```php
#[Mock]
private FooInterface $foo;
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
> because this package does modify the underlying mock object and changes the signature of the method.

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

// all hamcrest matchers included.
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
