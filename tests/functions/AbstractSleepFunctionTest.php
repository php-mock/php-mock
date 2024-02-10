<?php

namespace phpmock\functions;

use PHPUnit\Framework\TestCase;

/**
 * Tests AbstractSleepFunction and all its implementations.
 *
 * @author Markus Malkusch <markus@malkusch.de>
 * @link bitcoin:1335STSwu9hST4vcMRppEPgENMHD2r1REK Donations
 * @license http://www.wtfpl.net/txt/copying/ WTFPL
 * @see AbstractSleepFunction
 */
class AbstractSleepFunctionTest extends TestCase
{
    /**
     * Tests incrementation of all Incrementables
     */
    public function testSleepIncrementationOfAllIncrementables()
    {
        $value1 = new FixedValueFunction(1);
        $value2 = new FixedValueFunction(2);
        $sleep = new SleepFunction([$value1, $value2]);

        call_user_func($sleep->getCallable(), 1);

        $this->assertEquals(2, call_user_func($value1->getCallable()));
        $this->assertEquals(3, call_user_func($value2->getCallable()));
    }

    /**
     * Tests incrementation of Incrementables
     *
     * @param AbstractSleepFunction $sleepFunction Tested implementation.
     * @param int $amount                          Amount of time units.
     * @param mixed $expected                      Expected seconds.
     * @dataProvider provideTestSleepIncrementation
     */
    public function testSleepIncrementation(
        AbstractSleepFunction $sleepFunction,
        $amount,
        $expected
    ) {
        $value = new FixedValueFunction(0);
        $sleepFunction->addIncrementable($value);
        call_user_func($sleepFunction->getCallable(), $amount);
        $this->assertEquals($expected, call_user_func($value->getCallable()));
    }

    /**
     * Returns test cases for testSleepIncrementation().
     *
     * @return array Test cases.
     */
    public static function provideTestSleepIncrementation()
    {
        return [
            [new SleepFunction(), 1, 1],
            [new SleepFunction(), 0, 0],

            [new UsleepFunction(), 0, 0],
            [new UsleepFunction(), 1000, 0.001],
            [new UsleepFunction(), 1000000, 1],
        ];
    }
}
