<?php

namespace malkusch\phpmock\phpunit;

/**
 * Tests MockDelegateFunction.
 *
 * @author Markus Malkusch <markus@malkusch.de>
 * @link bitcoin:1335STSwu9hST4vcMRppEPgENMHD2r1REK Donations
 * @license WTFPL
 * @see MockDelegateFunction
 */
class MockDelegateFunctionTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Tests delegate() returns the mock's result.
     *
     * @test
     */
    public function testDelegateReturnsMockResult()
    {
        $expected = 3;
        $mock = $this->getMock('malkusch\phpmock\phpunit\MockDelegate');
        
        $mock->expects($this->once())
             ->method(MockDelegate::METHOD)
             ->willReturn($expected);
        
        $provider = new MockDelegateFunction($mock);
        $result = call_user_func($provider->getCallable());
        $this->assertEquals($expected, $result);
    }

    /**
     * Tests delegate() forwards the arguments.
     *
     * @test
     */
    public function testDelegateForwardsArguments()
    {
        $mock = $this->getMock('malkusch\phpmock\phpunit\MockDelegate');
        
        $mock->expects($this->once())
             ->method(MockDelegate::METHOD)
             ->with(1, 2);
        
        $provider = new MockDelegateFunction($mock);
        call_user_func($provider->getCallable(), 1, 2);
    }
}
