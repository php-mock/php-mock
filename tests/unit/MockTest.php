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
class MockTest extends AbstractMockTest
{
    
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
     * Test function call recording.
     *
     * @test
     */
    public function testRecording()
    {
        $mock = new Mock(
            __NAMESPACE__,
            "time",
            function () {
                return 1234;
            }
        );
        $mock->enable();
        
        $recorder = $mock->getRecorder();
        $this->assertEmpty($recorder->getCalls());

        time();
        $this->assertEquals([[]], $recorder->getCalls());

        time(true);
        $this->assertEquals([[],[true]], $recorder->getCalls());

        $function = function () {
        };
        $mock2 = new Mock(__NAMESPACE__, "abs", $function);
        $mock2->enable();
        $recorder = $mock2->getRecorder();
        $this->assertEmpty($recorder->getCalls());

        abs(12);
        $this->assertEquals([[12]], $recorder->getCalls());

    }
    
    /**
     * Tests enable().
     *
     * @test
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
     *
     * @test
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
     *
     * @test
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
