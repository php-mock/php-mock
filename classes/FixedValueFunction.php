<?php

namespace malkusch\phpmock;

/**
 * Mock function which returns always the same value.
 * 
 * @author Markus Malkusch <markus@malkusch.de>
 * @link bitcoin:1335STSwu9hST4vcMRppEPgENMHD2r1REK Donations
 * @license WTFPL
 */
class FixedValueFunction implements CallableProvider
{
    
    /**
     * @var mixed The fixed value for the function.
     */
    private $value;
    
    /**
     * Set the value.
     * 
     * @param mixed $value The value.
     */
    public function __construct($value = null)
    {
        $this->setValue($value);
    }
    
    /**
     * Returns this object as a callable for the mock function.
     * 
     * @return callable The callable for this object.
     */
    public function getCallable()
    {
        return array($this, "getValue");
    }

    /**
     * Set the value.
     * 
     * @param mixed $value The value.
     */
    public function setValue($value)
    {
        $this->value = $value;
    }
    
    /**
     * Returns the value.
     * 
     * @return mixed The value.
     */
    public function getValue()
    {
        return $this->value;
    }
}
