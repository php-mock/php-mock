<?php

namespace malkusch\phpmock\phpunit;

use malkusch\phpmock\AbstractMockTest;

/**
 * Tests PHPMock.
 *
 * @author Markus Malkusch <markus@malkusch.de>
 * @link bitcoin:1335STSwu9hST4vcMRppEPgENMHD2r1REK Donations
 * @license http://www.wtfpl.net/txt/copying/ WTFPL
 * @see PHPMock
 */
class PHPMockTest extends AbstractMockTest
{

    use PHPMock;
    
    protected function mockFunction($namespace, $functionName, callable $function)
    {
        $mock = $this->getFunctionMock($namespace, $functionName);
        $mock->expects($this->any())->willReturnCallback($function);
    }
    
    protected function disableMocks()
    {
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
}
