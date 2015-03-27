<?php

namespace malkusch\phpmock;

use malkusch\phpmock\functions\FixedDateFunction;
use malkusch\phpmock\functions\FixedMicrotimeFunction;
use malkusch\phpmock\functions\SleepFunction;
use malkusch\phpmock\functions\UsleepFunction;

/**
 * Builds a sleep(), usleep(), date(), time() and microtime() mock environment.
 *
 * In this environment sleep() and usleep() don't sleep for real. Instead
 * they return immediatly and increase the amount of time in the mocks for
 * date(), time() and microtime().
 *
 * Example:
 * <code>
 * namespace foo;
 *
 * use malkusch\phpmock\SleepEnvironmentBuilder;
 *
 * $builder = new SleepEnvironmentBuilder();
 * $builder->setNamespace(__NAMESPACE__)
 *         ->setTimestamp(1417011228);
 *
 * $environment = $builder->build();
 * $environment->enable();
 *
 * // This won't delay the test for 10 seconds, but increase time().
 * sleep(10);
 *
 * assert(1417011228 + 10 == time());
 * </code>
 *
 * @author Markus Malkusch <markus@malkusch.de>
 * @link bitcoin:1335STSwu9hST4vcMRppEPgENMHD2r1REK Donations
 * @license http://www.wtfpl.net/txt/copying/ WTFPL
 */
class SleepEnvironmentBuilder
{

    /**
     * @var string The namespace for the mock environment.
     */
    private $namespace;

    /**
     * @var mixed the timestamp.
     */
    private $timestamp;

    /**
     * Sets the namespace for the mock environment.
     *
     * @param string $namespace The namespace for the mock environment.
     * @return SleepEnvironmentBuilder
     */
    public function setNamespace($namespace)
    {
        $this->namespace = $namespace;
        return $this;
    }

    /**
     * Sets the mocked timestamp.
     *
     * If not set the mock will use the current time at creation time.
     * The timestamp can be an int, a float with microseconds or a string
     * in the microtime() format.
     *
     * @param mixed $timestamp The timestamp.
     * @return SleepEnvironmentBuilder
     */
    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;
        return $this;
    }

    /**
     * Builds a sleep(), usleep(), date(), time() and microtime() mock environment.
     *
     * @return MockEnvironment
     */
    public function build()
    {
        $environment = new MockEnvironment();

        $builder = new MockBuilder();
        $builder->setNamespace($this->namespace);

        // microtime() mock
        $microtime = new FixedMicrotimeFunction($this->timestamp);
        $builder->setName("microtime")
                ->setFunctionProvider($microtime);
        $environment->addMock($builder->build());

        // time() mock
        $builder->setName("time")
                ->setFunction([$microtime, "getTime"]);
        $environment->addMock($builder->build());

        // date() mock
        $date = new FixedDateFunction($this->timestamp);
        $builder->setName("date")
                ->setFunctionProvider($date);
        $environment->addMock($builder->build());

        $incrementables = [$microtime, $date];

        // sleep() mock
        $builder->setName("sleep")
                ->setFunctionProvider(new SleepFunction($incrementables));
        $environment->addMock($builder->build());

        // usleep() mock
        $builder->setName("usleep")
                ->setFunctionProvider(new UsleepFunction($incrementables));
        $environment->addMock($builder->build());

        return $environment;
    }
}
