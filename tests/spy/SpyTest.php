<?php

namespace phpmock\spy;

use phpmock\Mock;
use phpmock\AbstractMockTestCase;

/**
 * Tests the Spy.
 *
 * @author Markus Malkusch <markus@malkusch.de>
 * @link bitcoin:1335STSwu9hST4vcMRppEPgENMHD2r1REK Donations
 * @license http://www.wtfpl.net/txt/copying/ WTFPL
 * @see Spy
 */
class SpyTest extends AbstractMockTestCase
{
    protected function defineFunction($namespace, $functionName)
    {
        $mock = new Spy($namespace, $functionName, function () {
        });
        $mock->define();
    }

    protected function mockFunction($namespace, $functionName, callable $function)
    {
        $mock = new Spy($namespace, $functionName, $function);
        $mock->enable();
    }

    protected function disableMocks()
    {
        Mock::disableAll();
    }

    /**
     * Tests spying.
     *
     * @param array    $expected
     * @param string   $name
     * @param callable $invocations
     * @dataProvider provideTestGetInvocations
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('provideTestGetInvocations')]
    public function testGetInvocations(array $expected, $name, callable $invocations)
    {
        $spy = new Spy(__NAMESPACE__, $name);
        $spy->enable();
        call_user_func($invocations);
        $this->assertEquals($expected, $spy->getInvocations());
    }

    /**
     * Returns test cases for testGetInvocations().
     *
     * @return array Test cases for testGetInvocations.
     */
    public static function provideTestGetInvocations()
    {
        eval("function testGetInvocations_noParameters() { return 123; }");
        eval("function testGetInvocations_oneParameter(\$a) { return \$a + 1; }");
        eval("function testGetInvocations_twoParameters(\$a, \$b) { return \$a + \$b; }");
        eval("function testGetInvocations_optionalParameter(\$a = null) { return \$a; }");

        return [
            [
                [],
                "testGetInvocations_noParameters",
                function () {
                }
            ],
            [
                [new Invocation([], 123)],
                "testGetInvocations_noParameters",
                function () {
                    testGetInvocations_noParameters();
                }
            ],
            [
                [
                    new Invocation([], 123),
                    new Invocation([], 123),
                ],
                "testGetInvocations_noParameters",
                function () {
                    testGetInvocations_noParameters();
                    testGetInvocations_noParameters();
                }
            ],
            [
                [new Invocation([1], 2)],
                "testGetInvocations_oneParameter",
                function () {
                    testGetInvocations_oneParameter(1);
                }
            ],
            [
                [new Invocation([1, 2], 3)],
                "testGetInvocations_twoParameters",
                function () {
                    testGetInvocations_twoParameters(1, 2);
                }
            ],
            [
                [new Invocation([], null)],
                "testGetInvocations_optionalParameter",
                function () {
                    testGetInvocations_optionalParameter();
                }
            ],
            [
                [new Invocation([123], 123)],
                "testGetInvocations_optionalParameter",
                function () {
                    testGetInvocations_optionalParameter(123);
                }
            ],
        ];
    }

    /**
     * Tests the default function.
     */
    public function testDefaultFunction()
    {
        eval("function testDefaultFunction() { return 123; }");
        $spy = new Spy(__NAMESPACE__, "testDefaultFunction");
        $spy->enable();

        $result = testDefaultFunction();
        $this->assertEquals(123, $result);
    }

    /**
     * An exception should still be recorded.
     */
    public function testException()
    {
        eval('function testException($foo) { throw new \Exception(); }');
        $spy = new Spy(__NAMESPACE__, "testException");
        $spy->enable();

        try {
            testException("foo");
            $this->fail("Expected exception");
        } catch (\Exception $e) {
            $invocation = $spy->getInvocations()[0];

            $this->assertEquals(["foo"], $invocation->getArguments());
            $this->assertNull($invocation->getReturn());
            $this->assertTrue($invocation->isExceptionThrown());
            $this->assertEquals($e, $invocation->getException());
        }
    }

    /**
     * Test the invocation of a none exception call.
     */
    public function testNoException()
    {
        eval("function testNoException() { }");
        $spy = new Spy(__NAMESPACE__, "testNoException");
        $spy->enable();

        testNoException();

        $invocation = $spy->getInvocations()[0];
        $this->assertFalse($invocation->isExceptionThrown());
        $this->assertNull($invocation->getException());
    }
}
