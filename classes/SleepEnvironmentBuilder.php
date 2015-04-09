<?php

namespace phpmock;

/**
 * @deprecated since 0.8, use phpmock\environment\SleepEnvironmentBuilder.
 * @see environment\SleepEnvironmentBuilder
 */
class SleepEnvironmentBuilder extends environment\SleepEnvironmentBuilder
{

    public function __construct()
    {
        trigger_error("Use phpmock\\environment\\SleepEnvironmentBuilder", E_USER_DEPRECATED);
    }
}
