<?php

namespace malkusch\phpmock;

/**
 * Tests MicrotimeMock.
 *
 * @author Markus Malkusch <markus@malkusch.de>
 * @link bitcoin:1335STSwu9hST4vcMRppEPgENMHD2r1REK Donations
 * @license WTFPL
 * @see MicrotimeMock
 */
class MicrotimeMockTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var MicrotimeMock The microtime mock
     */
    private $mock;
    
    protected function setup()
    {
        $this->mock = new MicrotimeMock(__NAMESPACE__);
        $this->mock->enable();
    }
    
    protected function tearDown()
    {
        $this->mock->disable();
    }

    /**
     * Test microtime(true).
     * 
     * @param float $timestamp The timestamp
     * @test
     * @dataProvider provideTestMicrotimeAsFloat
     */
    public function testMicrotimeAsFloat($timestamp)
    {
        $this->mock->setMicrotimeAsFloat($timestamp);
        $this->assertEquals($timestamp, microtime(true));
    }
    
    /**
     * Provide test cases for testMicrotimeAsFloat().
     * 
     * @return float[][] timestamps
     */
    public function provideTestMicrotimeAsFloat()
    {
        return array(
            array(1),
            array(1.0),
            array(1.00000001),
            array(1.01),
            array(1.09),
            array(1.2),
            array(1.3),
            array(1.85168900),
            array(1.9),
            array(1.91),
            array(1.99),
            array(1.99999999),
        );
    }

     /**
     * Test microtime().
     * 
     * @param string $timestamp The timestamp
     * @test
     * @dataProvider provideTestMicrotime
     */
    public function testMicrotime($timestamp)
    {
        $this->mock->setMicrotime($timestamp);
        $this->assertEquals($timestamp, microtime());
    }
    
    /**
     * Provide test cases for testMicrotime().
     * 
     * @return string[][] timestamps
     */
    public function provideTestMicrotime()
    {
        return array(
            array("0.85168900 1416961047"),
            array("0 0"),
            array("0 1"),
            array("0.1 1"),
        );
    }
    
    /**
     * Tests setting a float and microtime().
     * 
     * @param type $float
     * @param type $string
     * 
     * @test
     * @dataProvider provideFloatAndStrings
     */
    public function testSetFloatGetString($float, $string)
    {
        $this->mock->setMicrotimeAsFloat($float);
        $this->assertEquals($string, microtime());
    }
    
    /**
     * Tests setting a string and microtime(true).
     * 
     * @param type $float
     * @param type $string
     * 
     * @test
     * @dataProvider provideFloatAndStrings
     */
    public function testSetStringGetFloat($float, $string)
    {
        $this->mock->setMicrotime($string);
        $this->assertEquals($float, microtime(true));
    }
    
    /**
     * Returns Test cases for testSetFloatGetString and testSetStringGetFloat
     * 
     * @return array
     */
    public function provideFloatAndStrings()
    {
        return array(
            array(1.0,        "0.00000000 1"),
            array(1.00000001, "0.00000001 1"),
            array(1.00000009, "0.00000009 1"),
            array(1.1,        "0.10000000 1"),
            array(1.11,       "0.11000000 1"),
            array(1.9,        "0.90000000 1"),
            array(1.99999999, "0.99999999 1"),
        );
    }
    
    /**
     * Test the initialization.
     * 
     * @test
     */
    public function testSetup()
    {
        $this->assertGreaterThan(microtime(true), \microtime(true));
        $this->assertGreaterThan(0, microtime(true));
    }
}
