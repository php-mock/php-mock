<?php

namespace malkusch\phpmock;

/**
 * Tests DateMock.
 *
 * @author Markus Malkusch <markus@malkusch.de>
 * @link bitcoin:1335STSwu9hST4vcMRppEPgENMHD2r1REK Donations
 * @license WTFPL
 * @see DateMock
 */
class DateMockTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var DateMock The date mock
     */
    private $mock;
    
    protected function setup()
    {
        $this->mock = new DateMock(__NAMESPACE__);
        $this->mock->enable();
    }
    
    protected function tearDown()
    {
        $this->mock->disable();
    }

    /**
     * Test date().
     * 
     * @test
     */
    public function testDate()
    {
        $this->mock->setTime(strtotime("2014-11-26"));
        $this->assertEquals("2014-11-26", date("Y-m-d"));
    }
    
    /**
     * Test the initialization.
     * 
     * @test
     */
    public function testSetup()
    {
        $format = "Y-m-d";
        $this->assertEquals(date($format), \date($format));
    }
}
