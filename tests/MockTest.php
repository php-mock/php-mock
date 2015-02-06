<?php

namespace malkusch\phpmock;

/**
 * Tests Mock.
 *
 * @author Markus Malkusch <markus@malkusch.de>
 * @link bitcoin:1335STSwu9hST4vcMRppEPgENMHD2r1REK Donations
 * @license http://www.wtfpl.net/txt/copying/ WTFPL
 * @see Mock
 */
class MockTest extends \PHPUnit_Framework_TestCase
{
    
    /**
     * @var Mock
     */
    private $mock;
    
    protected function setUp()
    {
        $this->mock = new Mock(
            __NAMESPACE__,
            "time",
            function () {
                return 1234;
            }
        );
        $this->mock->enable();
    }
    
    protected function tearDown()
    {
        $this->mock->disable();
    }

    /**
     * Tests define().
     *
     * @test
     */
    public function testDefine()
    {
        $mock = new Mock(__NAMESPACE__, "abs", "sqrt");
        $function = __NAMESPACE__ . '\abs';
        
        $this->assertFalse(function_exists($function));
        $mock->define();
        $this->assertTrue(function_exists($function));
        $this->assertEquals(1, abs(-1));
    }
    
    /**
     * Test function call recording.
     *
     * @test
     */
    public function testRecording()
    {
        $recorder = $this->mock->getRecorder();
        $this->assertEmpty($recorder->getCalls());

        time();
        $this->assertEquals(array(array()), $recorder->getCalls());

        time(true);
        $this->assertEquals(array(array(), array()), $recorder->getCalls());

        $mock = new Mock(__NAMESPACE__, "abs", 'emptyFunc');
        $mock->enable();
        $recorder = $mock->getRecorder();
        $this->assertEmpty($recorder->getCalls());

        abs(12);
        $this->assertEquals(array(array(12)), $recorder->getCalls());

    }
    
    /**
     * Tests enable().
     *
     * @test
     */
    public function testEnable()
    {
        $this->assertEquals(1234, time());
    }
    
    /**
     * Tests failing enabling an already enabled mock.
     *
     * @expectedException malkusch\phpmock\MockEnabledException
     * @test
     */
    public function testFailEnable()
    {
        $this->mock->enable();
    }
    
    /**
     * Tests disabling and enabling again.
     *
     * @test
     */
    public function testReenable()
    {
        $this->mock->disable();
        $this->mock->enable();
        $this->assertEquals(1234, time());
    }

    /**
     * Tests disable().
     *
     * @test
     */
    public function testDisable()
    {
        $this->mock->disable();
        $this->assertNotEquals(1234, time());
    }
    
    /**
     * Tests disableAll().
     *
     * @test
     */
    public function testDisableAll()
    {
        $mock2 = new Mock(__NAMESPACE__, "min", "max");
        $mock2->enable();

        Mock::disableAll();

        $this->assertNotEquals(1234, time());
        $this->assertEquals(1, min(array(1, 2)));
    }

    /**
     * Test getArgumentsList().
     *
     * @test
     * @dataProvider gettingArgumentsListProvider
     */
    public function testGettingArgumentsList($name, $arguments)
    {
        $mock = new Mock(__NAMESPACE__, $name, '\\emptyFunc');

        $class = new \ReflectionClass($mock);
        $method = $class->getMethod('getArgumentsList');
        $method->setAccessible(true);

        $this->assertEquals($arguments, $method->invoke($mock, array()));
    }

    public function gettingArgumentsListProvider()
    {
        return array(
            array('exec', 'array($command, &$output, &$return_value)'),
            array('time', 'array()'),
            array('highlight_string', 'array($string, $return)'),

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
        $mock = new Mock(__NAMESPACE__, $name, 'emptyFunc');

        $class = new \ReflectionClass($mock);
        $method = $class->getMethod('getParametersList');
        $method->setAccessible(true);

        $this->assertEquals($parametersList, $method->invoke($mock, array()));
    }

    public function gettingParametersListProvider()
    {
        return array(
            array('exec', '$command, &$output = \'optionalParameter\', &$return_value = \'optionalParameter\''),
            array('time', ''),
            array('highlight_string', '$string, $return = \'optionalParameter\''),
        );
    }

    /**
     * Test whether passing by reference does work
     *
     * @test
     */
    public function testPassingByReference()
    {
        $mock = new Mock(__NAMESPACE__, 'exec', function($a, &$b, &$c) {
            $b[] = 'test1';
            $b[] = 'test2';
            $c = 'test';
        });

        $mock->enable();
        $b = array();
        $c = '';

        exec('test', $b, $c);
        $this->assertEquals(array('test1', 'test2'), $b);
        $this->assertEquals('test', $c);

    }
}
