<?php

namespace malkusch\phpmock\phpunit;

/**
 * Simple interface which allows to build a PHPUnit mock.
 *
 * @author Markus Malkusch <markus@malkusch.de>
 * @link bitcoin:1335STSwu9hST4vcMRppEPgENMHD2r1REK Donations
 * @license WTFPL
 * @internal
 */
interface MockDelegate
{

    /**
     * The delegation method name.
     */
    const METHOD = "delegate";
    
    /**
     * A mocked function will redirect its call to this method.
     *
     * @return mixed Returns the function output.
     */
    public function delegate();
}
