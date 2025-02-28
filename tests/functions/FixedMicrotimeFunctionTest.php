<?php

namespace phpmock\functions;

use phpmock\MockBuilder;
use PHPUnit\Framework\TestCase;

/**
 * Tests FixedMicrotimeFunction.
 *
 * @author Markus Malkusch <markus@malkusch.de>
 * @link bitcoin:1335STSwu9hST4vcMRppEPgENMHD2r1REK Donations
 * @license http://www.wtfpl.net/txt/copying/ WTFPL
 * @see FixedMicrotimeFunction
 */
class FixedMicrotimeFunctionTest extends TestCase
{
    /**
     * Tests setMicrotime().
     */
    public function testSetMicrotime()
    {
        $function = new FixedMicrotimeFunction();
        $function->setMicrotime("0.00000001 1");
        $this->assertEquals("0.00000001 1", $function->getMicrotime());
    }

    /**
     * Tests setMicrotimeAsFloat().
     */
    public function testSetMicrotimeAsFloat()
    {
        $function = new FixedMicrotimeFunction();
        $function->setMicrotimeAsFloat(1.00000001);
        $this->assertEquals(1.00000001, $function->getMicrotime(true));
    }

    /**
     * Tests getMicrotime().
     */
    public function testGetMicrotime()
    {
        $function = new FixedMicrotimeFunction();
        $function->setMicrotimeAsFloat(1.00000001);
        $this->assertEquals(1.00000001, $function->getMicrotime(true));
        $this->assertEquals("0.00000001 1", $function->getMicrotime());
    }

    /**
     * Tests getCallable()
     */
    public function testGetCallable()
    {
        $function = new FixedMicrotimeFunction();
        $function->setMicrotimeAsFloat(1.00000001);

        $builder = new MockBuilder();
        $builder->setNamespace(__NAMESPACE__)
                ->setName("microtime")
                ->setFunctionProvider($function);

        $mock = $builder->build();
        $mock->enable();
        $this->assertEquals("0.00000001 1", microtime());
        $this->assertEquals(1.00000001, microtime(true));

        $mock->disable();
    }

    /**
     * Tests initializing with the current timestamp
     */
    public function testConstructCurrentTime()
    {
        $function = new FixedMicrotimeFunction();

        $this->assertGreaterThan($function->getMicrotime(true), \microtime(true));
        $this->assertGreaterThan(0, $function->getMicrotime(true));
    }

    /**
     * Tests exception for invalid argument in constructor.
     * @dataProvider provideTestConstructFailsForInvalidArgument
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('provideTestConstructFailsForInvalidArgument')]
    public function testConstructFailsForInvalidArgument($timestamp)
    {
        $this->expectException(\InvalidArgumentException::class);
        new FixedMicrotimeFunction($timestamp);
    }

    /**
     * Returns test cases for testConstructFailsForInvalidArgument()
     *
     * @return array Test cases.
     */
    public static function provideTestConstructFailsForInvalidArgument()
    {
        return [
            [true],
            [new \stdClass()]
        ];
    }

    /**
     * Tests initializing with a timestamp.
     *
     * @param mixed $timestamp The tested timestamp.
     * @param float $expected  The expected timestamp.
     * @dataProvider provideTestConstruct
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('provideTestConstruct')]
    public function testConstruct($timestamp, $expected)
    {
        $function = new FixedMicrotimeFunction($timestamp);

        $this->assertEquals($expected, $function->getMicrotime(true));
    }

    /**
     * Provides test cases for testConstruct().
     *
     * @return array
     */
    public static function provideTestConstruct()
    {
        return [
            ["0.00000001 1", 1.00000001],
            [1.00000001, 1.00000001],
        ];
    }
}
