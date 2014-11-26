<?php

namespace foo;

use malkusch\phpmock\TimeMock;

/**
 * Tests the example from the documentation.
 *
 * @author Markus Malkusch <markus@malkusch.de>
 * @link bitcoin:1335STSwu9hST4vcMRppEPgENMHD2r1REK Donations
 * @license WTFPL
 */
class ExampleTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Tests the example from the documentation.
     * 
     * @test
     */
    public function testExample()
    {
        $mock = new TimeMock(__NAMESPACE__);
        try {
            $mock->setTime(1234);
            $mock->enable();

            assert(time() == 1234);
            
            $this->assertEquals(1234, time());
            
        } finally {
            $mock->disable();
            
        }
    }
}
