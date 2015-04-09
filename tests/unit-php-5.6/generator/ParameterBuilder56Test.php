<?php

namespace phpmock\generator;

/**
 * Tests ParameterBuilder for PHP-5.6.
 *
 * @author Markus Malkusch <markus@malkusch.de>
 * @link bitcoin:1335STSwu9hST4vcMRppEPgENMHD2r1REK Donations
 * @license http://www.wtfpl.net/txt/copying/ WTFPL
 * @see ParameterBuilder
 */
class ParameterBuilder56Test extends \PHPUnit_Framework_TestCase
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
        $this->assertEquals($expectedBody, $builder->getBodyParameters());
        $this->assertEquals($expectedSignature, $builder->getSignatureParameters());
    }
    
    /**
     * Returns test cases for testBuild().
     *
     * @return string[][][] The test cases.
     */
    public function provideTestBuild()
    {
        // @codingStandardsIgnoreStart

        function testPHPVariadics1(...$one)
        {
        }

        function testPHPVariadics2($one, ...$two)
        {
        }

        function testPHPVariadics3($one, $two = 2, ...$three)
        {
        }

        function testPHPVariadics4(&$one, $two = 2, ...$three)
        {
        }
        
        // @codingStandardsIgnoreEnd
        
        return [
            ["", "", "min"],
            ["", "", __NAMESPACE__."\\testPHPVariadics1"],
            ['$one', '$one', __NAMESPACE__."\\testPHPVariadics2"],
            [
                sprintf(
                    "\$one, \$two = '%s'",
                    MockFunctionGenerator::DEFAULT_ARGUMENT
                ),
                '$one, $two',
                __NAMESPACE__."\\testPHPVariadics3"
            ],
            [
                sprintf(
                    "&\$one, \$two = '%s'",
                    MockFunctionGenerator::DEFAULT_ARGUMENT
                ),
                '&$one, $two',
                __NAMESPACE__."\\testPHPVariadics4"
            ],
        ];
    }
}
