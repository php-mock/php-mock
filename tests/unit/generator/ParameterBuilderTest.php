<?php

namespace phpmock\generator;

/**
 * Tests ParameterBuilder.
 *
 * @author Markus Malkusch <markus@malkusch.de>
 * @link bitcoin:1335STSwu9hST4vcMRppEPgENMHD2r1REK Donations
 * @license http://www.wtfpl.net/txt/copying/ WTFPL
 * @see ParameterBuilder
 */
class ParameterBuilderTest extends \PHPUnit_Framework_TestCase
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
        // @codingStandardsIgnoreStart

        function testNoParameter()
        {
        }

        function testOneParameter($one)
        {
        }

        function testTwoParameters($one, $two)
        {
        }

        function testOptionalParameters1($one = 1)
        {
        }

        function testOptionalParameters2($one = 1, $two = 2)
        {
        }

        function testOptionalParameters3($one, $two = 2)
        {
        }

        function testOptionalParameters4($one = 1, $two)
        {
        }
        
        function testReference1(&$one)
        {
        }

        function testReference2(&$one, $two)
        {
        }

        function testReference3($one, &$two)
        {
        }

        function testReference4(&$one, &$two)
        {
        }

        function testCombined($one, &$two, $three = 3, &$four = 4)
        {
        }
        
        // @codingStandardsIgnoreEnd
        
        return [
            ["", "", __NAMESPACE__."\\testNoParameter"],
            ['$one', '$one', __NAMESPACE__."\\testOneParameter"],
            ['$one, $two', '$one, $two', __NAMESPACE__."\\testTwoParameters"],
            ['$one, $two', '$one, $two', __NAMESPACE__."\\testTwoParameters"],
            ['&$one', '&$one', __NAMESPACE__."\\testReference1"],
            ['&$one, $two', '&$one, $two', __NAMESPACE__."\\testReference2"],
            ['$one, &$two', '$one, &$two', __NAMESPACE__."\\testReference3"],
            ['&$one, &$two', '&$one, &$two', __NAMESPACE__."\\testReference4"],
            [
                sprintf(
                    "\$command, &\$output = '%1\$s', &\$return_value = '%1\$s'",
                    MockFunctionGenerator::DEFAULT_ARGUMENT
                ),
                "\$command, &\$output, &\$return_value",
                "exec"
            ],
            [
                sprintf(
                    "\$one = '%s'",
                    MockFunctionGenerator::DEFAULT_ARGUMENT
                ),
                '$one',
                __NAMESPACE__."\\testOptionalParameters1"
            ],
            [
                sprintf(
                    "\$one = '%1\$s', \$two = '%1\$s'",
                    MockFunctionGenerator::DEFAULT_ARGUMENT
                ),
                '$one, $two',
                __NAMESPACE__."\\testOptionalParameters2"
            ],
            [
                sprintf(
                    "\$one, \$two = '%s'",
                    MockFunctionGenerator::DEFAULT_ARGUMENT
                ),
                '$one, $two',
                __NAMESPACE__."\\testOptionalParameters3"
            ],
            [
                sprintf(
                    "\$one, \$two",
                    MockFunctionGenerator::DEFAULT_ARGUMENT
                ),
                '$one, $two',
                __NAMESPACE__."\\testOptionalParameters4"
            ],
            [
                sprintf(
                    "\$one, &\$two, \$three = '%1\$s', &\$four = '%1\$s'",
                    MockFunctionGenerator::DEFAULT_ARGUMENT
                ),
                '$one, &$two, $three, &$four',
                __NAMESPACE__."\\testCombined"
            ],
        ];
    }
}
