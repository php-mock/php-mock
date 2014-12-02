<?php

namespace malkusch\phpmock\functions;

/**
 * Abstract class for sleep() and usleep() functions.
 * 
 * @author Markus Malkusch <markus@malkusch.de>
 * @link bitcoin:1335STSwu9hST4vcMRppEPgENMHD2r1REK Donations
 * @license WTFPL
 */
abstract class AbstractSleepFunction implements FunctionProvider
{
    
    /**
     * @var Incrementable[] Observing Incrementables.
     */
    private $incrementables = array();
    
    /**
     * Sets the Incrementable objects.
     * 
     * @param Incrementable[] $incrementables Observing Incrementables.
     * @see addIncrementable()
     */
    public function __construct(array $incrementables = array())
    {
        $this->incrementables = $incrementables;
    }
    
    public function getCallable()
    {
        return array($this, "sleep");
    }
    
    /**
     * Mock function.
     * 
     * A call will increase all registered Increment objects.
     * 
     * @param int $amount Amount of time units.
     * @internal
     */
    public function sleep($amount)
    {
        foreach ($this->incrementables as $incrementable) {
            $incrementable->increment($this->convertToSeconds($amount));
        }
    }

    /**
     * Converts the sleep() paramater into seconds.
     * 
     * @param int $amount Amount of time units.
     * @return mixed Seconds.
     * @internal
     */
    abstract protected function convertToSeconds($amount);

    /**
     * Adds an Incrementable object.
     * 
     * These objects are observing this function and get notified by
     * increasing the amount of passed time. Incrementables are used
     * for time() and microtime() mocks.
     * 
     * @param Incrementable $incrementable Observing Incrementable.
     */
    public function addIncrementable(Incrementable $incrementable)
    {
        $this->incrementables[] = $incrementable;
    }
}
