<?php

namespace phpmock;

/**
 * @deprecated since 0.8, use phpmock\environment\MockEnvironment.
 * @see environment\MockEnvironment
 */
class MockEnvironment extends environment\MockEnvironment
{
    
    public function __construct(Mock $mocks = array())
    {
        parent::__construct($mocks);
        
        trigger_error("Use phpmock\\environment\\MockEnvironment", E_USER_DEPRECATED);
    }
}
