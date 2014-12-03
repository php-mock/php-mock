<?php

namespace malkusch\phpmock;

/**
 * Tests Mock.
 *
 * @author Markus Malkusch <markus@malkusch.de>
 * @link bitcoin:1335STSwu9hST4vcMRppEPgENMHD2r1REK Donations
 * @license WTFPL
 * @see Mock
 */
class MockTest extends \PHPUnit_Framework_TestCase
{
    
    /**
     * @var Mock
     */
    private $mock;
    
    protected function setUp()
    {
        $this->mock = new Mock(
            __NAMESPACE__,
            "time",
            function () {
                return 1234;
            }
        );
        $this->mock->enable();
    }
    
    protected function tearDown()
    {
        $this->mock->disable();
    }

    /**
     * Tests define().
     * 
     * @test
     */
    public function testDefine()
    {
        $mock = new Mock(__NAMESPACE__, "abs", "sqrt");
        $function = __NAMESPACE__ . '\abs';
        
        $this->assertFalse(function_exists($function));
        $mock->define();
        $this->assertTrue(function_exists($function));
        $this->assertEquals(1, abs(-1));
    }
    
    /**
     * Test function call recording.
     * 
     * @test
     */
    public function testRecording()
    {
        $recorder = $this->mock->getRecorder();
        $this->assertEmpty($recorder->getCalls());
        
        time();
        $this->assertEquals(array(array()), $recorder->getCalls());
        
        time(true);
        $this->assertEquals(array(array(), array(true)), $recorder->getCalls());
    }
    
    /**
     * Tests enable().
     * 
     * @test
     */
    public function testEnable()
    {
        $this->assertEquals(1234, time());
    }
    
    /**
     * Tests failing enabling an already enabled mock.
     * 
     * @expectedException malkusch\phpmock\MockEnabledException
     * @test
     */
    public function testFailEnable()
    {
        $this->mock->enable();
    }
    
    /**
     * Tests disabling and enabling again.
     * 
     * @test
     */
    public function testReenable()
    {
        $this->mock->disable();
        $this->mock->enable();
        $this->assertEquals(1234, time());
    }

    /**
     * Tests disable().
     * 
     * @test
     */
    public function testDisable()
    {
        $this->mock->disable();
        $this->assertNotEquals(1234, time());
    }
}
