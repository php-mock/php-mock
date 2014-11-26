<?php

namespace malkusch\phpmock;

/**
 * Tests AbstractMock.
 *
 * @author Markus Malkusch <markus@malkusch.de>
 * @link bitcoin:1335STSwu9hST4vcMRppEPgENMHD2r1REK Donations
 * @license WTFPL
 * @see AbstractMock
 */
class AbstractMockTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Tests enable().
     * 
     * @test
     */
    public function testEnable()
    {
        $microtimeMock = new MicrotimeMock(__NAMESPACE__);
        $microtimeMock->enable();
        try {
            $microtimeMock->setMicrotime("0 0");
            $this->assertEquals("0 0", microtime());
            
        } finally {
            $microtimeMock->disable();
            
        }
    }

    /**
     * Tests disable().
     * 
     * @test
     */
    public function testDisable()
    {
        $microtimeMock = new MicrotimeMock(__NAMESPACE__);
        $microtimeMock->enable();
        $microtimeMock->setMicrotime("0 0");
        $microtimeMock->disable();
        
        $this->assertNotEquals("0 0", microtime());
    }
}
