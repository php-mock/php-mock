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

* PHP-5.3 or newer because of the `namespace` language feature.

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


# Installation

Use [Composer](https://getcomposer.org/):

```json
{
    "require": {
        "malkusch/php-mock": "0.1"
    }
}
```


# Mocks

You'll find the Mocks in the namespace [`malkusch\phpmock`](http://malkusch.github.io/php-mock/namespace-malkusch.phpmock.html).

* [`DateMock`](http://malkusch.github.io/php-mock/class-malkusch.phpmock.DateMock.html) mocks `date()`.

* [`TimeMock`](http://malkusch.github.io/php-mock/class-malkusch.phpmock.TimeMock.html) mocks `time()`.

* [`MicrotimeMock`](http://malkusch.github.io/php-mock/class-malkusch.phpmock.MicrotimeMock.html) mocks `microtime()`.

* [`RandMock`](http://malkusch.github.io/php-mock/class-malkusch.phpmock.RandMock.html) mocks `rand()`.

* [`MtRandMock`](http://malkusch.github.io/php-mock/class-malkusch.phpmock.MtRandMock.html) mocks `mt_rand()`.


# Usage

You'll have to instantiate the desired [`AbstractMock`](http://malkusch.github.io/php-mock/class-malkusch.phpmock.AbstractMock.html)
implementation. That object might have some configuration setters which will
determine the mock output. The function is not yet mocked. You'll have to
enabled it by calling [`enable()`](http://malkusch.github.io/php-mock/class-malkusch.phpmock.AbstractMock.html#_enable)
on the mock instance. When you are finished with that mock you
should disable it by calling [`disable()`](http://malkusch.github.io/php-mock/class-malkusch.phpmock.AbstractMock.html#_disable)
on the mock instance. 

This example illustrates mocking of the unqualified function `time()` in the 
namespace `foo`:

```php
<?php

namespace foo;

use malkusch\phpmock\TimeMock;

$mock = new TimeMock(__NAMESPACE__);
$mock->setTime(1417011228);

// The mock is not enabled yet.
assert (time() != 1417011228);

$mock->enable();
assert (time() == 1417011228);

// The mock is disabled and PHP's native time() is called.
$mock->disable();
assert (time() != 1417011228);
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

use malkusch\phpmock\TimeMock;

class AlarmTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var TimeMock The time() mock.
     */
    private $mock;
    
    protected function setup()
    {
        $this->mock = new TimeMock(__NAMESPACE__);
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

        $this->mock->setTime($timestamp - 1);
        $this->assertFalse($alarm->isRinging());

        $this->mock->setTime($timestamp);
        $this->assertTrue($alarm->isRinging());

        $this->mock->setTime($timestamp + 1);
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
