<?php

namespace malkusch\phpmock;

/**
 * Mocks PHP's time() function.
 *
 * @author Markus Malkusch <markus@malkusch.de>
 * @link bitcoin:1335STSwu9hST4vcMRppEPgENMHD2r1REK Donations
 * @license WTFPL
 * @see time()
 */
class TimeMock extends AbstractMock
{
    
    /**
     * @var int
     */
    private $timestamp;
    
    /**
     * Sets the mock's timestamp to the current time.
     * 
     * @param string $namespace The mock's namespace.
     */
    public function __construct($namespace)
    {
        parent::__construct($namespace);
        
        $this->timestamp = \time();
    }

    protected function getFunctionName()
    {
        return "time";
    }
    
    /**
     * Set the timestamp.
     * 
     * @param int $timestamp Timestamp
     */
    public function setTime($timestamp)
    {
        $this->timestamp = $timestamp;
    }
    
    /**
     * Mocks PHP's time().
     * 
     * @return int
     */
    public function mockFunction()
    {
        return $this->timestamp;
    }
}
