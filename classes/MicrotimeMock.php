<?php

namespace malkusch\phpmock;

/**
 * Mocks PHP's microtime() function.
 *
 * @author Markus Malkusch <markus@malkusch.de>
 * @link bitcoin:1335STSwu9hST4vcMRppEPgENMHD2r1REK Donations
 * @license WTFPL
 * @see microtime()
 */
class MicrotimeMock extends AbstractMock
{
    
    /**
     * @var string
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
        
        $this->timestamp = \microtime();
    }

    protected function getFunctionName()
    {
        return "microtime";
    }
    
    /**
     * Set the microtime in PHP's string format.
     * 
     * @param string $microtime Microtime in PHP's string format.
     */
    public function setMicrotime($microtime)
    {
        $this->timestamp = $microtime;
    }
    
    /**
     * Set the microtime as float.
     * 
     * @param float $microtime Microtime as float.
     */
    public function setMicrotimeAsFloat($microtime)
    {
        $this->timestamp =
                sprintf("%0.8F %d", fmod($microtime, 1), $microtime);
    }

    /**
     * Mocks PHP's microtime().
     * 
     * @param bool $get_as_float if true return the time as float
     * @return mixed
     */
    public function mockFunction($get_as_float = false)
    {
        if (! $get_as_float) {
            return $this->timestamp;
            
        } else {
            list($usec, $sec) = explode(" ", $this->timestamp);
            return ((float)$usec + (float)$sec);
            
        }
    }
}
