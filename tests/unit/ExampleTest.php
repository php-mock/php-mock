<?php

namespace foo;

use phpmock\Mock;
use phpmock\MockBuilder;
use phpmock\MockRegistry;
use phpmock\functions\FixedValueFunction;
use phpmock\environment\SleepEnvironmentBuilder;

/**
 * Tests the example from the documentation.
 *
 * @author Markus Malkusch <markus@malkusch.de>
 * @link bitcoin:1335STSwu9hST4vcMRppEPgENMHD2r1REK Donations
 * @license http://www.wtfpl.net/txt/copying/ WTFPL
 */
class ExampleTest extends \PHPUnit_Framework_TestCase
{
    
    protected function tearDown()
    {
        MockRegistry::getInstance()->unregisterAll();
    }

    /**
     * Tests the example from the documentation.
     *
     * @test
     */
    public function testExample1()
    {
        $builder = new MockBuilder();
        $builder->setNamespace(__NAMESPACE__)
                ->setName("time")
                ->setFunction(
                    function () {
                        return 1234;
                    }
                );
                    
        $mock = $builder->build();
        $mock->enable();
        assert(time() == 1234);
        $this->assertEquals(1234, time());
    }

    /**
     * Tests the example from the documentation.
     *
     * @test
     */
    public function testExample2()
    {
        $builder = new MockBuilder();
        $builder->setNamespace(__NAMESPACE__)
                ->setName("time")
                ->setFunctionProvider(new FixedValueFunction(12345));
                    
        $mock = $builder->build();
        $mock->enable();
        assert(time() == 12345);
        $this->assertEquals(12345, time());
    }

    /**
     * Tests the example from the documentation.
     *
     * @test
     */
    public function testExample3()
    {
        $builder = new SleepEnvironmentBuilder();
        $builder->addNamespace(__NAMESPACE__)
                ->setTimestamp(12345);

        $environment = $builder->build();
        $environment->enable();
        
        sleep(10);

        assert(12345 + 10 == time());
        $this->assertEquals(12345 + 10, time());
    }
    
    /**
     * Tests the example from the documentation.
     *
     * @expectedException Exception
     */
    /*
    public function testExample4()
    {
        $function = function () {
            throw new \Exception();
        };
        $mock = new Mock(__NAMESPACE__, "time", $function);
        $mock->enable();
        try {
            time();

        } finally {
            $mock->disable();

        }
    }
     */
    
    /**
     * Tests the example from the documentation.
     *
     * @test
     */
    public function testExample5()
    {
        $time = new Mock(
            __NAMESPACE__,
            "time",
            function () {
                return 3;
            }
        );
        $time->enable();
        assert(3 == time());
    }
}
