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
    
    /**
     * Sets the namespace for the mock environment.
     *
     * @param string $namespace The namespace for the mock environment.
     * @return SleepEnvironmentBuilder
     * @deprecated since 0.8, use phpmock\environment\SleepEnvironmentBuilder::addNamespace().
     * @see environment\SleepEnvironmentBuilder::addNamespace()
     */
    public function setNamespace($namespace)
    {
        trigger_error("Use SleepEnvironmentBuilder::addNamespace().", E_USER_DEPRECATED);
        return $this->addNamespace($namespace);
    }
}
