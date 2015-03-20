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
        Mock::disableAll();
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
        $this->assertEquals([[]], $recorder->getCalls());

        time(true);
        $this->assertEquals([[],[true]], $recorder->getCalls());

        $function = function () {
        };
        $mock = new Mock(__NAMESPACE__, "abs", $function);
        $mock->enable();
        $recorder = $mock->getRecorder();
        $this->assertEmpty($recorder->getCalls());

        abs(12);
        $this->assertEquals([[12]], $recorder->getCalls());

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
        $this->assertEquals(1, min([1, 2]));
    }

    /**
     * Tests passing by value.
     *
     * @test
     */
    public function testPassingByValue()
    {
        $mock = new Mock(__NAMESPACE__, "sqrt", function ($a) {
            return $a + 1;
        });
        $mock->enable();
        
        // Tests passing directly the value.
        $this->assertEquals(3, sqrt(2));
    }

    /**
     * Test passing by reference.
     *
     * @test
     */
    public function testPassingByReference()
    {
        $mock = new Mock(__NAMESPACE__, "exec", function ($a, &$b, &$c) {
            $a   = "notExpected";
            $b[] = "test1";
            $b[] = "test2";
            $c = "test";
        });

        $mock->enable();
        $noReference = "expected";
        $b = [];
        $c = "";

        exec($noReference, $b, $c);
        $this->assertEquals(["test1", "test2"], $b);
        $this->assertEquals("test", $c);
        $this->assertEquals("test", $c);
        $this->assertEquals("expected", $noReference);
    }
    
    /**
     * Tests that the mock preserves the default argument
     *
     * @test
     */
    public function testPreserveArgumentDefaultValue()
    {
        $function = function ($input, $pad_length, $pad_string = " ") {
            return $pad_string;
        };
        $mock = new Mock(__NAMESPACE__, "str_pad", $function);
        $mock->enable();
        
        $result1 = str_pad("foo", 5);
        $this->assertEquals(" ", $result1);
        
        $mock->disable();
        $result2 = str_pad("foo", 5);
        $this->assertEquals("foo  ", $result2);
    }
    
    /**
     * Tests some methods which use the varname "...".
     *
     * @test
     */
    public function testCVariadic()
    {
        $mock = new Mock(__NAMESPACE__, "min", "max");
        $mock->define();
        
        $this->assertEquals(1, min(2, 1));
        $this->assertEquals(1, min([2, 1]));
        
        $mock->enable();

        $this->assertEquals(2, min(2, 1));
        $this->assertEquals(2, min([2, 1]));
    }
}
