<?php

namespace malkusch\phpmock;

/**
 * Tests MockBuilder.
 *
 * @author Markus Malkusch <markus@malkusch.de>
 * @link bitcoin:1335STSwu9hST4vcMRppEPgENMHD2r1REK Donations
 * @license WTFPL
 * @see MockBuilder
 */
class MockBuilderTest extends \PHPUnit_Framework_TestCase
{
    
    /**
     * Tests build().
     * 
     * @test
     */
    public function testBuild()
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
        try {
            $this->assertEquals(1234, time());
            
        } finally {
            $mock->disable();
            
        }
        
        $builder->setCallableProvider(new FixedValueFunction(123));
        $mock = $builder->build();
        $mock->enable();
        try {
            $this->assertEquals(123, time());
            
        } finally {
            $mock->disable();
            
        }
    }
}
