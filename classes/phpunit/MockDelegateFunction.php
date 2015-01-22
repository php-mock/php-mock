<?php

namespace malkusch\phpmock\phpunit;

use malkusch\phpmock\functions\FunctionProvider;

/**
 * Function provider which delegates to a mockable MockDelegate.
 *
 * @author Markus Malkusch <markus@malkusch.de>
 * @link bitcoin:1335STSwu9hST4vcMRppEPgENMHD2r1REK Donations
 * @license http://www.wtfpl.net/txt/copying/ WTFPL
 * @internal
 */
class MockDelegateFunction implements FunctionProvider
{
    
    /**
     * @var MockDelegate $delegate The mock.
     */
    private $delegate;
    
    /**
     * Injects the mock.
     *
     * @param MockDelegate $delegate The mock.
     */
    public function __construct(MockDelegate $delegate)
    {
        $this->delegate = $delegate;
    }

    public function getCallable()
    {
        $delegate = $this->delegate;
        return function () use ($delegate) {
            return call_user_func_array(
                array($delegate, MockDelegate::METHOD),
                func_get_args()
            );
        };
    }
}
