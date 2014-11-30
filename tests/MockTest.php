<?php

namespace malkusch\phpmock;

/**
 * Tests Mock.
 *
 * @author Markus Malkusch <markus@malkusch.de>
 * @link bitcoin:1335STSwu9hST4vcMRppEPgENMHD2r1REK Donations
 * @license WTFPL
 * @see Mock
 */
class MockTest extends \PHPUnit_Framework_TestCase
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
     * Tests enable().
     * 
     * @test
     */
    public function testEnable()
    {
        $this->assertEquals(1234, time());
    }

    /**
     * Tests disable().
     * 
     * @test
     */
    public function testDisable()
    {
        $this->mock->disable();
        $this->assertNotEquals(1234, time());
    }
}
