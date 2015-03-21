<?php

namespace malkusch\phpmock\phpunit;

/**
 * Tests MockDelegateFunctionBuilder.
 *
 * @author Markus Malkusch <markus@malkusch.de>
 * @link bitcoin:1335STSwu9hST4vcMRppEPgENMHD2r1REK Donations
 * @license http://www.wtfpl.net/txt/copying/ WTFPL
 * @see MockDelegateFunctionBuilder
 */
class MockDelegateFunctionBuilderTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Test build() defines a class.
     *
     * @test
     */
    public function testBuild()
    {
        $builder = new MockDelegateFunctionBuilder();
        $builder->build();
        $this->assertTrue(class_exists($builder->getFullyQualifiedClassName()));
    }

    /**
     * Test build() would never create the same class name.
     *
     * @test
     */
    public function testSubsequentCallsProduceDifferentClasses()
    {
        $builder = new MockDelegateFunctionBuilder();

        $builder->build();
        $class1 = $builder->getFullyQualifiedClassName();

        $builder->build();
        $class2 = $builder->getFullyQualifiedClassName();
        
        $builder2 = new MockDelegateFunctionBuilder();
        $builder2->build();
        $class3 = $builder2->getFullyQualifiedClassName();
        
        $this->assertNotEquals($class1, $class2);
        $this->assertNotEquals($class1, $class3);
        $this->assertNotEquals($class2, $class3);
    }
}
