<?php

namespace foo;

use malkusch\phpmock\MockBuilder;
use malkusch\phpmock\functions\FixedValueFunction;

/**
 * Tests the example from the documentation.
 *
 * @author Markus Malkusch <markus@malkusch.de>
 * @link bitcoin:1335STSwu9hST4vcMRppEPgENMHD2r1REK Donations
 * @license WTFPL
 */
class ExampleTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Tests the example from the documentation.
     * 
     * @test
     */
    public function testExample1()
    {
        $builder = new MockBuilder();
        $builder->setNamespace(__NAMESPACE__)
                ->setName("time")
                ->setFunction(
                    function () {
                        return 1234;
                    }
                );
                    
        $mock = $builder->build();
        $mock->enable();
        assert(time() == 1234);
        $this->assertEquals(1234, time());
            
        $mock->disable();
    }

    /**
     * Tests the example from the documentation.
     * 
     * @test
     */
    public function testExample2()
    {
        $builder = new MockBuilder();
        $builder->setNamespace(__NAMESPACE__)
                ->setName("time")
                ->setFunctionProvider(new FixedValueFunction(12345));
                    
        $mock = $builder->build();
        $mock->enable();
        assert(time() == 12345);
        $this->assertEquals(12345, time());
        
        $mock->disable();
    }
}
