<?php

namespace malkusch\phpmock;

/**
 * Tests SleepEnvironmentBuilder.
 *
 * @author Markus Malkusch <markus@malkusch.de>
 * @link bitcoin:1335STSwu9hST4vcMRppEPgENMHD2r1REK Donations
 * @license http://www.wtfpl.net/txt/copying/ WTFPL
 * @see SleepEnvironmentBuilder
 */
class SleepEnvironmentBuilderTest extends \PHPUnit_Framework_TestCase
{
    
    /**
     * @var MockEnvironment The build environment.
     */
    private $environment;
    
    protected function setUp()
    {
        $builder = new SleepEnvironmentBuilder();
        $builder->setNamespace(__NAMESPACE__)
                ->setTimestamp(1234);
        
        $this->environment = $builder->build(__NAMESPACE__);
        $this->environment->enable();
    }

    protected function tearDown()
    {
        $this->environment->disable();
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
}
