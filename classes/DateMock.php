<?php

namespace malkusch\phpmock;

/**
 * Mocks PHP's date() function.
 * 
 * This mock ignores completely date's timestamp parameter. I.e. it will
 * always return the formated date for the mock's timestamp.
 *
 * @author Markus Malkusch <markus@malkusch.de>
 * @link bitcoin:1335STSwu9hST4vcMRppEPgENMHD2r1REK Donations
 * @license WTFPL
 * @see date()
 */
class DateMock extends AbstractMock
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
        
        $this->timestamp = time();
    }

    protected function getFunctionName()
    {
        return "date";
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
     * Mocks PHP's date().
     * 
     * This implementation ignores completely the $timestamp parameter if any
     * given.
     * 
     * @return string
     */
    public function mockFunction()
    {
        return \date(func_get_arg(0), $this->timestamp);
    }
}
