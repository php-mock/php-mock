<?php

namespace malkusch\phpmock\phpunit;

use Mockery;

/**
 * Tests MockObjectProxyTest.
 *
 * @author Markus Malkusch <markus@malkusch.de>
 * @link bitcoin:1335STSwu9hST4vcMRppEPgENMHD2r1REK Donations
 * @license http://www.wtfpl.net/txt/copying/ WTFPL
 * @see MockObjectProxyTest
 */
class MockObjectProxyTest extends \PHPUnit_Framework_TestCase
{
    
    /**
     * Resets Mockery mocks.
     */
    public function tearDown()
    {
        Mockery::close();
    }
    
    /**
     * Tests calling the proxy forwards the call to the subject.
     *
     * @param string $method    The proxy method.
     * @param array  $arguments The optional arguments.
     *
     * @test
     * @dataProvider provideTestProxiedMethods
     */
    public function testProxiedMethods($method, array $arguments = [], $expected = "foo")
    {
        $mock     = Mockery::mock("PHPUnit_Framework_MockObject_MockObject");
        $proxy    = new MockObjectProxy($mock);
        
        $mock->shouldReceive($method)
             ->once()->withArgs($arguments)->andReturn($expected);
        
        $result = call_user_func_array([$proxy, $method], $arguments);
        $this->assertEquals($expected, $result);
    }

    /**
     * Returns the test cases for testProxiedMethods().
     *
     * @return array Test cases.
     */
    public function provideTestProxiedMethods()
    {
        $expects = Mockery::mock("PHPUnit_Framework_MockObject_Builder_InvocationMocker");
        $expects->shouldReceive("method")
                ->withArgs([MockObjectProxy::METHOD])
                ->andReturn($expects);
        
        return [
            ["__phpunit_getInvocationMocker"],
            ["__phpunit_setOriginalObject", ["bar"]],
            ["__phpunit_verify"],
            ["__phpunit_hasMatchers"],
            [
                "expects",
                [Mockery::mock("PHPUnit_Framework_MockObject_Matcher_Invocation")],
                $expects
            ],
        ];
    }
}
