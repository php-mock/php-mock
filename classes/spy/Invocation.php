<?php

namespace phpmock\spy;

/**
 * A function call with its arguments and result.
 *
 * @author Markus Malkusch <markus@malkusch.de>
 * @link bitcoin:1335STSwu9hST4vcMRppEPgENMHD2r1REK Donations
 * @license http://www.wtfpl.net/txt/copying/ WTFPL
 */
class Invocation
{
    
    /**
     * @var mixed The function call's return value.
     */
    private $return;
    
    /**
     * @var array The function call's arguments.
     */
    private $arguments;

    /**
     * Sets the arguments and return value
     *
     * @param array $arguments The arguments.
     * @param mixed $return    The return value.
     *
     * @internal
     */
    public function __construct(array $arguments, $return)
    {
        $this->arguments = $arguments;
        $this->return    = $return;
    }

    /**
     * Returns the arguments of a function call.
     *
     * @return array The arguments.
     */
    public function getArguments()
    {
        return $this->arguments;
    }

    /**
     * Returns the return value of a function call.
     *
     * @return mixed The return value.
     */
    public function getReturn()
    {
        return $this->return;
    }
}
