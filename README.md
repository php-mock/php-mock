# About PHP-Mock

PHP-Mock is a testing library which mocks non deterministic built-in PHP functions like
`time()` or `rand()`. This is achived by [PHP's namespace fallback policy](http://php.net/manual/en/language.namespaces.fallback.php):

> For functions […], PHP will fall back to global functions […] if a
> namespaced function […] does not exist.

PHP-Mock uses that feature by providing the namespaced function. I.e. you have
to be in a **non global namespace** context and call the function
**unqualified**:

```php
<?php

namespace foo;

$time = time(); // This call can be mocked, a call to \time() can't.
```

## Requirements

* PHP-5.4 or newer. There's also a [PHP-5.3 branch](https://github.com/malkusch/php-mock/tree/php-5.3).

* Only *unqualified* function calls in a namespace context can be mocked.
  E.g. a call for `time()` in the namespace `foo` is mockable,
  a call for `\time()` is not.

## Alternatives

If you can't rely on or just don't want to use the namespace fallback policy,
there are alternative techniques to mock built-in PHP functions:

* [**PHPBuiltinMock**](https://github.com/jadell/PHPBuiltinMock) relies on
  the [APD](http://php.net/manual/en/book.apd.php) extension.

* [**phpunit-mockfunction**](https://github.com/tcz/phpunit-mockfunction)
  uses the [runkit](http://php.net/manual/en/book.runkit.php) extension.

* [**vfsStream**](https://github.com/mikey179/vfsStream) is a stream wrapper for
  a virtual file system. This will help you write tests which covers PHP
  stream functions (e.g. `fread()` or `readdir()`).

# Installation

Use [Composer](https://getcomposer.org/):

```json
{
    "require": {
        "malkusch/php-mock": "0.2"
    }
}
```


# Usage

You find the API in the namespace [`malkusch\phpmock`](http://malkusch.github.io/php-mock/namespace-malkusch.phpmock.html).

Create a [`Mock`](http://malkusch.github.io/php-mock/class-malkusch.phpmock.Mock.html)
object. You can do this with the fluent API of [`MockBuilder`](http://malkusch.github.io/php-mock/class-malkusch.phpmock.MockBuilder.html):

* [`MockBuilder::setNamespace()`](http://malkusch.github.io/php-mock/class-malkusch.phpmock.MockBuilder.html#_setNamespace)
  sets the target namespace of the mocked function.

* [`MockBuilder::setName()`](http://malkusch.github.io/php-mock/class-malkusch.phpmock.MockBuilder.html#_setName)
  sets the name of the mocked function (e.g. `time()`).

* [`MockBuilder::setFunction()`](http://malkusch.github.io/php-mock/class-malkusch.phpmock.MockBuilder.html#_setFunction)
  sets the concrete mock implementation.

* [`MockBuilder::setFunctionProvider()`](http://malkusch.github.io/php-mock/class-malkusch.phpmock.MockBuilder.html#_setFunctionProvider)
  sets alternativly to `MockBuilder::setFunction()` the mock implementation as a
  [`FunctionProvider`](http://malkusch.github.io/php-mock/class-malkusch.phpmock.functions.FunctionProvider.html):

   * [`FixedValueFunction`](http://malkusch.github.io/php-mock/class-malkusch.phpmock.functions.FixedValueFunction.html)
     is a simple implementation which returns always the same value.

   * [`FixedMicrotimeFunction`](http://malkusch.github.io/php-mock/class-malkusch.phpmock.functions.FixedMicrotimeFunction.html)
     is a simple implementation which returns always the same microtime. This
     class is different to `FixedValueFunction` as it contains a converter for
     `microtime()`'s float and string format.

* [`MockBuilder::build()`](http://malkusch.github.io/php-mock/class-malkusch.phpmock.MockBuilder.html#_build)
  builds a `Mock` object.

After you have build your `Mock` object you have to call [`enable()`](http://malkusch.github.io/php-mock/class-malkusch.phpmock.Mock.html#_enable)
to enable the mock in the given namespace. When you are finished with that mock you
should disable it by calling [`disable()`](http://malkusch.github.io/php-mock/class-malkusch.phpmock.Mock.html#_disable)
on the mock instance. 

This example illustrates mocking of the unqualified function `time()` in the 
namespace `foo`:

```php
<?php

namespace foo;

use malkusch\phpmock\MockBuilder;

$builder = new MockBuilder();
$builder->setNamespace(__NAMESPACE__)
        ->setName("time")
        ->setFunction(
            function () {
                return 1417011228;
            }
        );
                    
$mock = $builder->build();

// The mock is not enabled yet.
assert (time() != 1417011228);

$mock->enable();
assert (time() == 1417011228);

// The mock is disabled and PHP's built-in time() is called.
$mock->disable();
assert (time() != 1417011228);
```

Instead of setting the mock function with `MockBuilder::setFunction()` you could also
use the existing [`FixedValueFunction`](http://malkusch.github.io/php-mock/class-malkusch.phpmock.functions.FixedValueFunction.html):

```php
<?php

namespace foo;

use malkusch\phpmock\MockBuilder;
use malkusch\phpmock\functions\FixedValueFunction;

$builder = new MockBuilder();
$builder->setNamespace(__NAMESPACE__)
        ->setName("time")
        ->setFunctionProvider(new FixedValueFunction(1417011228));

$mock = $builder->build();
```

## Unit testing

PHP-Mock is meant to be used for unit testing. You should always disable a mock
after the test case. Otherwise you change the global state and might break
subsequent tests. Use PHPUnit's `tearDown()` or PHP's `finally` to disable the
mock.

Let's assume we want to test a class `Alarm` which rings an alarm on the second
we set:

```php
<?php

namespace foo;

class Alarm
{

    private $timestamp;

    //…

    public function isRinging()
    {
        // Note: time() is an unqualified function name in the namespace foo.
        return time() == $this->timestamp;
    }
}
```

This would be the unit test for `Alarm::isRinging()`:

```php
<?php

namespace foo;

use malkusch\phpmock\MockBuilder;
use malkusch\phpmock\functions\FixedValueFunction;

class AlarmTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var Mock The time() mock.
     */
    private $mock;

    /**
     * @var FixedValueFunction The mock function.
     */
    private $time;
    
    protected function setup()
    {
        $this->time = new FixedValueFunction();
        $builder = new MockBuilder();

        $this->mock = $builder->setNamespace(__NAMESPACE__)
                              ->setName("time")
                              ->setFunctionProvider($this->time)
                              ->build();

        $this->mock->enable();
    }
    
    protected function tearDown()
    {
        $this->mock->disable();
    }

    public function testRingAlarm()
    {
        $timestamp = 1417011228;
        $alarm = new Alarm($timestamp);

        $this->time->setValue($timestamp - 1);
        $this->assertFalse($alarm->isRinging());

        $this->time->setValue($timestamp);
        $this->assertTrue($alarm->isRinging());

        $this->time->setValue($timestamp + 1);
        $this->assertFalse($alarm->isRinging());
    }

}
```


# License and authors

This project is free and under the WTFPL.
Responsable for this project is Markus Malkusch markus@malkusch.de.
This library was inspired by Fabian Schmengler's article
[*PHP: “Mocking” built-in functions like time() in Unit Tests*](http://www.schmengler-se.de/en/2011/03/php-mocking-built-in-functions-like-time-in-unit-tests/).

## Donations

If you like PHP-Mock and feel generous donate a few Bitcoins here:
[1335STSwu9hST4vcMRppEPgENMHD2r1REK](bitcoin:1335STSwu9hST4vcMRppEPgENMHD2r1REK)

[![Build Status](https://travis-ci.org/malkusch/php-mock.svg?branch=master)](https://travis-ci.org/malkusch/php-mock)
