<?php

// A different namespace
namespace malkusch\phpmock\test;

use malkusch\phpmock\MicrotimeMock;

/**
 * Tests AbstractMock in a different namespace.
 *
 * @author Markus Malkusch <markus@malkusch.de>
 * @link bitcoin:1335STSwu9hST4vcMRppEPgENMHD2r1REK Donations
 * @license WTFPL
 * @see AbstractMock
 */
class AbstractMockNamespaceTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Tests mocking in a different namespace.
     * 
     * @test
     */
    public function testNamespace()
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
}
