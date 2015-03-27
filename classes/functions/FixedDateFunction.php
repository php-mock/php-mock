<?php

namespace malkusch\phpmock\functions;

/**
 * Mock function for date() which returns always the same time.
 *
 * @author Markus Malkusch <markus@malkusch.de>
 * @link bitcoin:1335STSwu9hST4vcMRppEPgENMHD2r1REK Donations
 * @license http://www.wtfpl.net/txt/copying/ WTFPL
 */
class FixedDateFunction implements FunctionProvider, Incrementable
{

    /**
     * @var int the timestamp.
     */
    private $timestamp;

    /**
     * Set the timestamp.
     *
     * @param int $timestamp The timestamp, if ommited the current time.
     */
    public function __construct($timestamp = null)
    {
        if (is_null($timestamp)) {
            $timestamp = \time();
        }
        $this->timestamp = $timestamp;
    }

    /**
     * Returns this object as a callable for the mock function.
     *
     * @return callable The callable for this object.
     */
    public function getCallable()
    {
        return [$this, "getDate"];
    }

    /**
     * Returns a formatted date string.
     *
     * @param string $format The format of the outputted date string
     * @param int $timestamp The optional timestamp parameter as an integer timestamp. Default is $this->timestamp.
     *
     * @return bool|string Returns a formatted date string. If a non-numeric value is used for timestamp,
     * FALSE is returned and an E_WARNING level error is emitted.
     *
     * @see \date()
     */
    public function getDate($format, $timestamp = null)
    {
        if (is_null($timestamp)) {
            $timestamp = $this->timestamp;
        }
        return \date($format, $timestamp);
    }

    public function increment($increment)
    {
        $this->timestamp += $increment;
    }
}
