<?php

namespace phpmock;

/**
 * Tests Mock.
 *
 * @author Markus Malkusch <markus@malkusch.de>
 * @link bitcoin:1335STSwu9hST4vcMRppEPgENMHD2r1REK Donations
 * @license http://www.wtfpl.net/txt/copying/ WTFPL
 * @see Mock
 */
class MockTest extends AbstractMockTestCase
{
    protected function defineFunction($namespace, $functionName)
    {
        $mock = new Mock($namespace, $functionName, function () {
        });
        $mock->define();
    }

    protected function mockFunction($namespace, $functionName, callable $function)
    {
        $mock = new Mock($namespace, $functionName, $function);
        $mock->enable();
    }

    protected function disableMocks()
    {
        Mock::disableAll();
    }

    /**
     * Tests enable().
     */
    public function testEnable()
    {
        $mock = new Mock(
            __NAMESPACE__,
            "rand",
            function () {
                return 1234;
            }
        );
        $this->assertNotEquals(1234, rand());
        $mock->enable();
        $this->assertEquals(1234, rand());
    }

    /**
     * Tests disabling and enabling again.
     */
    public function testReenable()
    {
        $mock = new Mock(
            __NAMESPACE__,
            "time",
            function () {
                return 1234;
            }
        );
        $mock->enable();
        $mock->disable();
        $mock->enable();
        $this->assertEquals(1234, time());
    }

    /**
     * Tests disableAll().
     */
    public function testDisableAll()
    {
        $mock2 = new Mock(__NAMESPACE__, "min", "max");
        $mock2->enable();

        Mock::disableAll();

        $this->assertNotEquals(1234, time());
        $this->assertEquals(1, min([1, 2]));
    }
}
