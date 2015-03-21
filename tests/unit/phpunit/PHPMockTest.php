<?php

namespace malkusch\phpmock\phpunit;

/**
 * Tests PHPMock.
 *
 * @author Markus Malkusch <markus@malkusch.de>
 * @link bitcoin:1335STSwu9hST4vcMRppEPgENMHD2r1REK Donations
 * @license http://www.wtfpl.net/txt/copying/ WTFPL
 * @see PHPMock
 */
class PHPMockTest extends \PHPUnit_Framework_TestCase
{

    use PHPMock;
    
    /**
     * Tests building a simple mock.
     *
     * @test
     */
    public function testFunctionMock()
    {
        $time = $this->getFunctionMock(__NAMESPACE__, "time");
        $time->expects($this->once())->willReturn(3);
        
        $this->assertEquals(3, time());
    }
    
    /**
     * Tests automatic disabling of the mock.
     *
     * @depends testFunctionMock
     * @test
     */
    public function testFunctionMockDisablesMockedFunctions()
    {
        $this->assertNotEquals(3, time());
        
        $time = $this->getFunctionMock(__NAMESPACE__, "time");
        $time->expects($this->once());
        time();
    }
    
    /**
     * Tests building a mock with arguments.
     *
     * @test
     */
    public function testFunctionMockWithArguments()
    {
        $time = $this->getFunctionMock(__NAMESPACE__, "sqrt");
        $time->expects($this->once())->with(9)->willReturn(2);
        
        $this->assertEquals(2, sqrt(9));
    }
    
    /**
     * Tests failing an expectation.
     *
     * @test
     */
    public function testFunctionMockFailsExpectation()
    {
        try {
            $time = $this->getFunctionMock(__NAMESPACE__, "time");
            $time->expects($this->once());

            $time->__phpunit_verify();
            $this->fail("Expectation should fail");
        
        } catch (\PHPUnit_Framework_ExpectationFailedException $e) {
            time(); // satisfy the expectation

        }
    }

    /**
     * Tests passing by value.
     *
     * @test
     */
    public function testPassingByValue()
    {
        $mock = $this->getFunctionMock(__NAMESPACE__, "sqrt");
        $mock->expects($this->once())->willReturn(3);

        // Tests passing directly the value.
        $this->assertEquals(3, sqrt(2));
    }

    /**
     * Tests passing by reference.
     *
     * @test
     */
    public function testPassingByReference()
    {
        $mock = $this->getFunctionMock(__NAMESPACE__, "exec");
        $mock->expects($this->once())->willReturnCallback(
            function ($a, &$b, &$c) {
                $b = $a;
                $c = $a;
            }
        );

        exec("expected", $b, $c);
        $this->assertEquals("expected", $b);
        $this->assertEquals("expected", $c);
    }
    
    /**
     * Tests that the mock preserves the default argument
     *
     * @test
     */
    public function testPreserveArgumentDefaultValue()
    {
        eval('
            function testPreserveArgumentDefaultValue($b = "default") {
                return $b;
            }
        ');
        
        $mock = $this->getFunctionMock(__NAMESPACE__, "testPreserveArgumentDefaultValue");
        $mock->expects($this->once())->willReturnCallback(function ($arg = "expected") {
            return $arg;
        });
        $result = testPreserveArgumentDefaultValue();
        $this->assertEquals("expected", $result);
    }
}
