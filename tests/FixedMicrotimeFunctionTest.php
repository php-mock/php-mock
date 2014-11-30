<?php

namespace malkusch\phpmock;

/**
 * Tests FixedMicrotimeFunction.
 *
 * @author Markus Malkusch <markus@malkusch.de>
 * @link bitcoin:1335STSwu9hST4vcMRppEPgENMHD2r1REK Donations
 * @license WTFPL
 * @see FixedMicrotimeFunction
 */
class FixedMicrotimeFunctionTest extends \PHPUnit_Framework_TestCase
{
    
    /**
     * Tests setMicrotime().
     * 
     * @test
     */
    public function testSetMicrotime()
    {
        $function = new FixedMicrotimeFunction();
        $function->setMicrotime("0.00000001 1");
        $this->assertEquals("0.00000001 1", $function->getMicrotime());
    }
    
    /**
     * Tests setMicrotimeAsFloat().
     * 
     * @test
     */
    public function testSetMicrotimeAsFloat()
    {
        $function = new FixedMicrotimeFunction();
        $function->setMicrotimeAsFloat(1.00000001);
        $this->assertEquals(1.00000001, $function->getMicrotime(true));
    }
    
    /**
     * Tests getMicrotime().
     * 
     * @test
     */
    public function testGetMicrotime()
    {
        $function = new FixedMicrotimeFunction();
        $function->setMicrotimeAsFloat(1.00000001);
        $this->assertEquals(1.00000001, $function->getMicrotime(true));
        $this->assertEquals("0.00000001 1", $function->getMicrotime());
    }
    
    /**
     * Tests getCallable()
     * 
     * @test
     */
    public function testGetCallable()
    {
        $function = new FixedMicrotimeFunction();
        $function->setMicrotimeAsFloat(1.00000001);
        
        $builder = new MockBuilder();
        $builder->setNamespace(__NAMESPACE__)
                ->setName("microtime")
                ->setCallableProvider($function);
                    
        $mock = $builder->build();
        $mock->enable();
        try {
            $this->assertEquals("0.00000001 1", microtime());
            $this->assertEquals(1.00000001, microtime(true));
            
        } finally {
            $mock->disable();
            
        }
    }
    
    /**
     * Tests initializing with the current timestamp
     * 
     * @test
     */
    public function testConstructCurrentTime()
    {
        $function = new FixedMicrotimeFunction();
        
        $this->assertGreaterThan($function->getMicrotime(true), \microtime(true));
        $this->assertGreaterThan(0, $function->getMicrotime(true));
    }
    
    /**
     * Tests initializing with a timestamp.
     * 
     * @param mixed $timestamp The tested timestamp.
     * @param float $expected  The expected timestamp.
     * 
     * @test
     * @dataProvider provideTestConstruct
     */
    public function testConstruct($timestamp, $expected)
    {
        $function = new FixedMicrotimeFunction($timestamp);
        
        $this->assertEquals($expected, $function->getMicrotime(true));
    }
    
    /**
     * Provides test cases for testConstruct().
     * 
     * @return array
     */
    public function provideTestConstruct()
    {
        return array(
            array("0.00000001 1", 1.00000001),
            array(1.00000001, 1.00000001),
        );
    }
}
