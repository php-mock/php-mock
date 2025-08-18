<?php

namespace phpmock\spy;

use PHPUnit\Framework\TestCase;

/**
 * Tests the Invocation.
 *
 * @author Markus Malkusch <markus@malkusch.de>
 * @link bitcoin:1335STSwu9hST4vcMRppEPgENMHD2r1REK Donations
 * @license http://www.wtfpl.net/txt/copying/ WTFPL
 * @see Invocation
 */
class InvocationTest extends TestCase
{
    public function testInvocationWithoutException()
    {
        $arguments = [1, 2];
        $return = 3;
        $invocation = new Invocation($arguments, $return);

        self::assertSame($arguments, $invocation->getArguments());
        self::assertSame($return, $invocation->getReturn());
        self::assertNull($invocation->getException());
        self::assertFalse($invocation->isExceptionThrown());
    }

    public function testInvocationWithException()
    {
        $arguments = [1, 2];
        $return = 3;
        $exception = new \Exception('Test exception');
        $invocation = new Invocation($arguments, $return, $exception);

        self::assertSame($arguments, $invocation->getArguments());
        self::assertSame($return, $invocation->getReturn());
        self::assertSame($exception, $invocation->getException());
        self::assertTrue($invocation->isExceptionThrown());
    }
}
