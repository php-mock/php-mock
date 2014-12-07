<?php

namespace malkusch\phpmock;

/**
 * Call recorder.
 *
 * @author Markus Malkusch <markus@malkusch.de>
 * @link bitcoin:1335STSwu9hST4vcMRppEPgENMHD2r1REK Donations
 * @license WTFPL
 */
class Recorder
{
   
    /**
     * @var array Recorded calls.
     */
    private $calls = array();
    
    /**
     * Records a function calls and its arguments.
     *
     * @param array $arguments The function arguments.
     */
    public function record(array $arguments)
    {
        $this->calls[] = $arguments;
    }

    /**
     * Returns the recorded function calls and its arguments.
     *
     * @return array The recorded function arguments.
     */
    public function getCalls()
    {
        return $this->calls;
    }
}
