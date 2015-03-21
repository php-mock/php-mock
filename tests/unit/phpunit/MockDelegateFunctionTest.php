<?php

namespace malkusch\phpmock\phpunit;

/**
 * Tests MockDelegateFunction.
 *
 * @author Markus Malkusch <markus@malkusch.de>
 * @link bitcoin:1335STSwu9hST4vcMRppEPgENMHD2r1REK Donations
 * @license http://www.wtfpl.net/txt/copying/ WTFPL
 * @see MockDelegateFunction
 */
class MockDelegateFunctionTest extends \PHPUnit_Framework_TestCase
{
    
    /**
     * @var string The class name of a generated class.
     */
    private $className;
    
    protected function setUp()
    {
        parent::setUp();
        
        $builder = new MockDelegateFunctionBuilder();
        $builder->build();
        $this->className = $builder->getFullyQualifiedClassName();
    }

    /**
     * Tests delegate() returns the mock's result.
     *
     * @test
     */
    public function testDelegateReturnsMockResult()
    {
        $expected = 3;
        $mock     = $this->getMockForAbstractClass($this->className);
        
        $mock->expects($this->once())
             ->method(MockObjectProxy::METHOD)
             ->willReturn($expected);
        
        $result = call_user_func($mock->getCallable());
        $this->assertEquals($expected, $result);
    }

    /**
     * Tests delegate() forwards the arguments.
     *
     * @test
     */
    public function testDelegateForwardsArguments()
    {
        $mock = $this->getMockForAbstractClass($this->className);
        
        $mock->expects($this->once())
             ->method(MockObjectProxy::METHOD)
             ->with(1, 2);
        
        call_user_func($mock->getCallable(), 1, 2);
    }
}
