<?php

namespace phpmock\generator;

use PHPUnit\Framework\TestCase;

/**
 * Tests MockFunctionGenerator.
 *
 * @author Markus Malkusch <markus@malkusch.de>
 * @link bitcoin:1335STSwu9hST4vcMRppEPgENMHD2r1REK Donations
 * @license http://www.wtfpl.net/txt/copying/ WTFPL
 * @see MockFunctionGenerator
 */
class MockFunctionGeneratorTest extends TestCase
{

    /**
     * Tests removeDefaultArguments().
     *
     * @param array $expected  The expected result.
     * @param array $arguments The input arguments.
     *
     * @test
     * @dataProvider provideTestRemoveDefaultArguments
     */
    public function testRemoveDefaultArguments(array $expected, array $arguments)
    {
        MockFunctionGenerator::removeDefaultArguments($arguments);
        $this->assertEquals($expected, $arguments);
    }

    /**
     * Returns test cases for testRemoveDefaultArguments().
     *
     * @return The test cases.
     */
    public function provideTestRemoveDefaultArguments()
    {
        return[
            [[], []],
            [[1], [1]],
            [[1, 2], [1, 2]],
            [[null], [null]],
            [[], [MockFunctionGenerator::DEFAULT_ARGUMENT]],
            [[], [MockFunctionGenerator::DEFAULT_ARGUMENT, MockFunctionGenerator::DEFAULT_ARGUMENT]],
            [[1], [1, MockFunctionGenerator::DEFAULT_ARGUMENT]],
            [[null], [null, MockFunctionGenerator::DEFAULT_ARGUMENT]],
            [[1], [1, MockFunctionGenerator::DEFAULT_ARGUMENT, MockFunctionGenerator::DEFAULT_ARGUMENT]],
        ];
    }
}
