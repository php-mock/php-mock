<?php

namespace phpmock;

use phpmock\functions\FixedValueFunction;
use PHPUnit\Framework\TestCase;

/**
 * Tests MockBuilder.
 *
 * @author Markus Malkusch <markus@malkusch.de>
 * @link bitcoin:1335STSwu9hST4vcMRppEPgENMHD2r1REK Donations
 * @license http://www.wtfpl.net/txt/copying/ WTFPL
 * @see MockBuilder
 */
class MockBuilderTest extends TestCase
{
    /**
     * Tests build().
     */
    public function testBuild()
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
        $this->assertEquals(1234, time());
        $mock->disable();


        $builder->setFunctionProvider(new FixedValueFunction(123));
        $mock = $builder->build();
        $mock->enable();
        $this->assertEquals(123, time());
        $mock->disable();
    }
}
