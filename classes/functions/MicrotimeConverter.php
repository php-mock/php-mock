<?php

namespace malkusch\phpmock\functions;

/**
 * Converts PHP's microtime string format into a float and vice versa.
 *
 * @author Markus Malkusch <markus@malkusch.de>
 * @link bitcoin:1335STSwu9hST4vcMRppEPgENMHD2r1REK Donations
 * @license WTFPL
 * @internal
 */
class MicrotimeConverter
{
    
    /**
     * Converts a string microtime into a float.
     *
     * @param string $microtime The microtime.
     * @return float The microtime as float.
     */
    public function convertStringToFloat($microtime)
    {
        list($usec, $sec) = explode(" ", $microtime);
        return ((float)$usec + (float)$sec);
    }
    
    /**
     * Converts a float microtime in PHP's microtime() string format.
     *
     * @param float $microtime The microtime.
     * @return String The microtime as string.
     */
    public function convertFloatToString($microtime)
    {
        return sprintf("%0.8F %d", fmod($microtime, 1), $microtime);
    }
}
