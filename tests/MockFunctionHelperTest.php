<?php

namespace malkusch\phpmock;

/**
 * Tests MockFunctionHelper.
 *
 * @author Markus Malkusch <markus@malkusch.de>
 * @link bitcoin:1335STSwu9hST4vcMRppEPgENMHD2r1REK Donations
 * @license http://www.wtfpl.net/txt/copying/ WTFPL
 * @see MockFunctionHelper
 */
class MockFunctionHelperTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Test getArgumentsList().
     *
     * @test
     * @dataProvider gettingArgumentsListProvider
     */
    public function testGettingArgumentsList($name, $arguments)
    {
        $function = function () {
        };
        $mock = new Mock(__NAMESPACE__, $name, $function);
        $helper = new MockFunctionHelper($mock);

        $class = new \ReflectionClass($helper);
        $method = $class->getMethod("getParametersList");
        $method->setAccessible(true);

        $this->assertEquals($arguments, $method->invoke($helper, false));
    }

    /**
     * Returns test cases for testGettingArgumentsList().
     * 
     * @return array Test cases.
     */
    public function gettingArgumentsListProvider()
    {
        return array(
            array("exec", '$command, &$output, &$return_value'),
            array("time", ""),
            array("highlight_string", '$string, $return'),
        );
    }

    /**
     * Test Parameters List
     *
     * @test
     * @dataProvider gettingParametersListProvider
     */
    public function testGettingParametersList($name, $parametersList)
    {
        $function = function () {
        };
        $mock = new Mock(__NAMESPACE__, $name, $function);
        $helper = new MockFunctionHelper($mock);

        $class = new \ReflectionClass($helper);
        $method = $class->getMethod("getParametersList");
        $method->setAccessible(true);

        $this->assertEquals($parametersList, $method->invoke($helper, true));
    }

    /**
     * Returns test cases for testGettingParametersList().
     * 
     * @return array Test cases.
     */
    public function gettingParametersListProvider()
    {
        return array(
            array("exec", '$command, &$output = \'optionalParameter\', &$return_value = \'optionalParameter\''),
            array("time", ""),
            array("highlight_string", '$string, $return = \'optionalParameter\''),
        );
    }
    
}
