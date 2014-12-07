<?php

// A different namespace
namespace malkusch\phpmock\test;

use malkusch\phpmock\Mock;

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
    
    protected function setUp()
    {
        $this->mock = new Mock(
            __NAMESPACE__,
            "time",
            function () {
                return 1234;
            }
        );
        $this->mock->enable();
    }
    
    protected function tearDown()
    {
        $this->mock->disable();
    }

    /**
     * Tests mocking in a different namespace.
     *
     * @test
     */
    public function testNamespace()
    {
        $this->assertEquals(1234, time());
    }
}
