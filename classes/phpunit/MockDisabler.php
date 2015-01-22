<?php

namespace malkusch\phpmock\phpunit;

use malkusch\phpmock\Mock;

/**
 * Test listener for PHPUnit integration.
 *
 * This class disables the mock function after a test was run.
 *
 * @author Markus Malkusch <markus@malkusch.de>
 * @link bitcoin:1335STSwu9hST4vcMRppEPgENMHD2r1REK Donations
 * @license WTFPL
 * @internal
 */
class MockDisabler extends \PHPUnit_Framework_BaseTestListener
{

    /**
     * @var Mock The function mock.
     */
    private $mock;
    
    /**
     * Sets the function mock.
     *
     * @param Mock $mock The function mock.
     */
    public function __construct(Mock $mock)
    {
        $this->mock = $mock;
    }
    
    /**
     * Disables the function mock.
     *
     * @param \PHPUnit_Framework_Test $test The test.
     * @param int                     $time The test duration.
     *
     * @see Mock::disable()
     */
    public function endTest(\PHPUnit_Framework_Test $test, $time)
    {
        parent::endTest($test, $time);
        
        $this->mock->disable();
    }
}
