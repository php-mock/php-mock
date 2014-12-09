<?php

// A different namespace
namespace malkusch\phpmock\test;

use malkusch\phpmock\Mock;
use malkusch\phpmock\MockBuilder;
use malkusch\phpmock\functions\FixedValueFunction;

/**
 * Tests Mock in a different namespace.
 *
 * @author Markus Malkusch <markus@malkusch.de>
 * @link bitcoin:1335STSwu9hST4vcMRppEPgENMHD2r1REK Donations
 * @license WTFPL
 * @see Mock
 */
class MockNamespaceTest extends \PHPUnit_Framework_TestCase
{
    
    /**
     * @var Mock
     */
    private $mock;
    
    /**
     * @var MockBuilder
     */
    private $builder;
    
    protected function setUp()
    {
        $this->builder = new MockBuilder();
        $this->builder
                ->setName("time")
                ->setFunctionProvider(new FixedValueFunction(1234));
    }
    
    protected function tearDown()
    {
        if (! empty($this->mock)) {
            $this->mock->disable();
            unset($this->mock);
            
        }
    }

    /**
     * Tests defining mocks in a different namespace.
     *
     * @test
     * @dataprovider provideTestNamespace
     * @runInSeparateProcess
     */
    public function testDefiningNamespaces()
    {
        $this->builder->setNamespace(__NAMESPACE__);
        $this->mock = $this->builder->build();
        $this->mock->enable();
        
        $this->assertEquals(1234, time());
    }

    /**
     * Tests redefining mocks in a different namespace.
     *
     * @test
     * @dataprovider provideTestNamespace
     */
    public function testRedefiningNamespaces()
    {
        $this->builder->setNamespace(__NAMESPACE__);
        $this->mock = $this->builder->build();
        $this->mock->enable();
        
        $this->assertEquals(1234, time());
    }
    
    /**
     * Provides namespaces for testNamespace().
     *
     * @return string[][] Namespaces.
     */
    public function provideTestNamespace()
    {
        return array(
            array(__NAMESPACE__),
            array('malkusch\phpmock\test'),
            array('\malkusch\phpmock\test'),
            array('malkusch\phpmock\test\\'),
            array('\malkusch\phpmock\test\\')
        );
    }
}
