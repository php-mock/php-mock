<?php

namespace phpmock\environment;

use phpmock\TestCaseTrait;
use PHPUnit\Framework\TestCase;

/**
 * Tests SleepEnvironmentBuilder.
 *
 * @author Markus Malkusch <markus@malkusch.de>
 * @link bitcoin:1335STSwu9hST4vcMRppEPgENMHD2r1REK Donations
 * @license http://www.wtfpl.net/txt/copying/ WTFPL
 * @see SleepEnvironmentBuilder
 */
class SleepEnvironmentBuilderTest extends TestCase
{
    use TestCaseTrait;

    /**
     * @var MockEnvironment The build environment.
     */
    private $environment;

    protected function setUpCompat()
    {
        $builder = new SleepEnvironmentBuilder();
        $builder->addNamespace(__NAMESPACE__)
                ->setTimestamp(1234);

        $this->environment = $builder->build();
        $this->environment->enable();
    }

    protected function tearDownCompat()
    {
        $this->environment->disable();
    }

    /**
     * Tests mocking functions accross several namespaces.
     *
     * @test
     */
    public function testAddNamespace()
    {
        $builder = new SleepEnvironmentBuilder();
        $builder->addNamespace(__NAMESPACE__)
                ->addNamespace("testAddNamespace")
                ->setTimestamp(1234);

        $this->environment->disable();
        $this->environment = $builder->build();
        $this->environment->enable();

        $time = time();
        \testAddNamespace\sleep(123);
        sleep(123);

        $this->assertEquals(2 * 123 + $time, time());
        $this->assertEquals(2 * 123 + $time, \testAddNamespace\time());
    }

    /**
     * Tests sleep()
     *
     * @test
     */
    public function testSleep()
    {
        $time = time();
        $microtime = microtime(true);
        sleep(1);

        $this->assertEquals($time + 1, time());
        $this->assertEquals($microtime + 1, microtime(true));
        $this->assertEquals($time + 1, date("U"));
    }

    /**
     * Tests usleep()
     *
     * @param int $microseconds Microseconds.
     *
     * @test
     * @dataProvider provideTestUsleep
     */
    public function testUsleep($microseconds)
    {
        $time = time();
        $microtime = microtime(true);
        usleep($microseconds);

        $delta = $microseconds / 1000000;
        $this->assertEquals((int)($time + $delta), time());
        $this->assertEquals((int)($time + $delta), date("U"));
        $this->assertEquals($microtime + $delta, microtime(true));
    }

    /**
     * Returns test cases for testUsleep().
     *
     * @return int[][] Test cases.
     */
    public function provideTestUsleep()
    {
        return [
            [1000],
            [999999],
            [1000000],
        ];
    }

    /**
     * Tests date()
     *
     * @test
     */
    public function testDate()
    {
        $time = time();
        sleep(100);

        $this->assertEquals($time + 100, date("U"));
    }
}
