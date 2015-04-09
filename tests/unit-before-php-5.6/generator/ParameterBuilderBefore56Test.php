<?php

namespace phpmock\generator;

/**
 * Tests ParameterBuilder for <PHP-5.6.
 *
 * @author Markus Malkusch <markus@malkusch.de>
 * @link bitcoin:1335STSwu9hST4vcMRppEPgENMHD2r1REK Donations
 * @license http://www.wtfpl.net/txt/copying/ WTFPL
 * @see ParameterBuilder
 */
class ParameterBuilderBefore56Test extends \PHPUnit_Framework_TestCase
{
    
    /**
     * Tests build().
     *
     * @param string $expectedSignature The expected signature parameters.
     * @param string $expectedBody      The expected body parameters.
     * @param string $function          The function name.
     *
     * @dataProvider provideTestBuild
     * @test
     */
    public function testBuild($expectedSignature, $expectedBody, $function)
    {
        $builder = new ParameterBuilder();
        $builder->build($function);
        $this->assertEquals($expectedSignature, $builder->getSignatureParameters());
        $this->assertEquals($expectedBody, $builder->getBodyParameters());
    }
    
    /**
     * Returns test cases for testBuild().
     *
     * @return string[][][] The test cases.
     */
    public function provideTestBuild()
    {
        return [
            [
                sprintf(
                    "\$arg1, \$arg2 = '%s'",
                    MockFunctionGenerator::DEFAULT_ARGUMENT
                ),
                '$arg1, $arg2',
                "min"
            ],
        ];
    }
}
