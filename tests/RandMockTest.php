<?php

namespace malkusch\phpmock;

/**
 * Tests RandMock.
 *
 * @author Markus Malkusch <markus@malkusch.de>
 * @link bitcoin:1335STSwu9hST4vcMRppEPgENMHD2r1REK Donations
 * @license WTFPL
 * @see RandMock
 */
class RandMockTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var RandMock The rand mock
     */
    private $mock;
    
    protected function setup()
    {
        $this->mock = new RandMock(__NAMESPACE__);
        $this->mock->enable();
    }
    
    protected function tearDown()
    {
        $this->mock->disable();
    }

    /**
     * Test rand().
     * 
     * @test
     */
    public function testRand()
    {
        $this->mock->setNumber(1234);
        $this->assertEquals(1234, rand());
    }
}
