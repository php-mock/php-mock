<?php

namespace phpmock\environment;

use phpmock\Mock;
use phpmock\MockBuilder;
use phpmock\functions\FixedValueFunction;
use phpmock\TestCaseTrait;
use PHPUnit\Framework\TestCase;

/**
 * Tests MockEnvironment.
 *
 * @author Markus Malkusch <markus@malkusch.de>
 * @link bitcoin:1335STSwu9hST4vcMRppEPgENMHD2r1REK Donations
 * @license http://www.wtfpl.net/txt/copying/ WTFPL
 * @see MockEnvironment
 */
class MockEnvironmentTest extends TestCase
{
    use TestCaseTrait;

    /**
     * @var MockEnvironment The tested environment.
     */
    private $environment;

    protected function setUpCompat()
    {
        $builder = new MockBuilder();
        $builder->setNamespace(__NAMESPACE__)
                ->setFunctionProvider(new FixedValueFunction(1234));

        $this->environment = new MockEnvironment();
        $this->environment->addMock($builder->setName("time")->build());
        $this->environment->addMock($builder->setName("rand")->build());
    }

    protected function tearDownCompat()
    {
        $this->environment->disable();
    }

    /**
     * Tests enable()
     *
     * @test
     */
    public function testEnable()
    {
        $this->environment->enable();

        $this->assertEquals(1234, time());
        $this->assertEquals(1234, rand());
    }

    /**
     * Tests define()
     *
     * @test
     */
    public function testDefine()
    {
        $this->environment->addMock(
            new Mock(__NAMESPACE__, "testDefine", function () {
            })
        );

        $this->environment->define();

        $this->assertTrue(function_exists("phpmock\\environment\\time"));
        $this->assertTrue(function_exists("phpmock\\environment\\rand"));
        $this->assertTrue(function_exists("phpmock\\environment\\testDefine"));
    }

    /**
     * Tests disable()
     *
     * @test
     */
    public function testDisable()
    {
        $this->environment->enable();
        $this->environment->disable();

        $this->assertNotEquals(1234, time());

        // Note: There's a tiny chance that this assertion might fail.
        $this->assertNotEquals(1234, rand());
    }
}
