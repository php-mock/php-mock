<?php

namespace malkusch\phpmock;

/**
 * Tests MtRandMock.
 *
 * @author Markus Malkusch <markus@malkusch.de>
 * @link bitcoin:1335STSwu9hST4vcMRppEPgENMHD2r1REK Donations
 * @license WTFPL
 * @see MtRandMock
 */
class MtRandMockTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var RandMock The mt_rand mock
     */
    private $mock;
    
    protected function setup()
    {
        $this->mock = new MtRandMock(__NAMESPACE__);
        $this->mock->enable();
    }
    
    protected function tearDown()
    {
        $this->mock->disable();
    }

    /**
     * Test mt_rand().
     * 
     * @test
     */
    public function testRand()
    {
        $this->mock->setNumber(1234);
        $this->assertEquals(1234, mt_rand());
    }
}
