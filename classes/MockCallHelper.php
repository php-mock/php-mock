<?php

namespace malkusch\phpmock;

/**
 * Helper for calling the mock from the defined function.
 *
 * @author Markus Malkusch <markus@malkusch.de>
 * @link bitcoin:1335STSwu9hST4vcMRppEPgENMHD2r1REK Donations
 * @license http://www.wtfpl.net/txt/copying/ WTFPL
 * @internal
 */
class MockCallHelper
{

    /**
     * Calls the enabled mock, or the built-in function otherwise.
     *
     * @param string $functionName          The function name.
     * @param string $canonicalFunctionName The canonical function name.
     * @param array  $arguments             The arguments.
     *
     * @return mixed The result of the called function.
     * @see Mock::define()
     */
    public static function call($functionName, $canonicalFunctionName, &$arguments)
    {
        $registry = MockRegistry::getInstance();
        $mock     = $registry->getMock($canonicalFunctionName);

        foreach ($arguments as $key => $arg) {
            if ($arg === Mock::DEFAULT_ARGUMENT) {
                unset($arguments[$key]);
            }
        }
        if (empty($mock)) {
            // call the built-in function if the mock was not enabled.
            return call_user_func_array($functionName, $arguments);
        
        } else {
            // call the mock function.
            return $mock->call($arguments);
        }
    }
}
