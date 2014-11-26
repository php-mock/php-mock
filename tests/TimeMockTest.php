<?php

namespace malkusch\phpmock;

/**
 * Tests TimeMock.
 *
 * @author Markus Malkusch <markus@malkusch.de>
 * @link bitcoin:1335STSwu9hST4vcMRppEPgENMHD2r1REK Donations
 * @license WTFPL
 * @see TimeMock
 */
class TimeMockTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var TimeMock The time mock
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

    /**
     * Test time().
     * 
     * @test
     */
    public function testTime()
    {
        $this->mock->setTime(1234);
        $this->assertEquals(1234, time());
    }
    
    /**
     * Test the initialization.
     * 
     * @test
     */
    public function testSetup()
    {
        $this->assertGreaterThanOrEqual(time(), \time());
        $this->assertLessThanOrEqual(time() + 1, \time());
    }
}
