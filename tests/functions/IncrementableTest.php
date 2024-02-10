<?php

namespace phpmock\functions;

use PHPUnit\Framework\TestCase;

/**
 * Tests Incrementable and all its implementations.
 *
 * @author Markus Malkusch <markus@malkusch.de>
 * @link bitcoin:1335STSwu9hST4vcMRppEPgENMHD2r1REK Donations
 * @license http://www.wtfpl.net/txt/copying/ WTFPL
 * @see Incrementable
 */
class IncrementableTest extends TestCase
{
    /**
     * Tests increment().
     *
     * @param mixed $expected               The expected value.
     * @param mixed $increment              The amount of increase.
     * @param Incrementable $incrementable  The tested Incrementable.
     * @param callable $getValue            The lambda for getting the value.
     * @dataProvider provideTestIncrement
     */
    public function testIncrement(
        $expected,
        $increment,
        Incrementable $incrementable,
        callable $getValue
    ) {
        $incrementable->increment($increment);
        $this->assertEquals($expected, $getValue($incrementable));
    }

    /**
     * Test cases for testIncrement().
     *
     * @return array Test cases.
     */
    public static function provideTestIncrement()
    {
        $getFixedValue = function (FixedValueFunction $function) {
            return call_user_func($function->getCallable());
        };
        $getMicrotime = function (FixedMicrotimeFunction $function) {
            return $function->getMicrotime(true);
        };
        $getDate = function (FixedDateFunction $function) {
            return call_user_func($function->getCallable(), "U");
        };
        return [
            [1, 1, new FixedValueFunction(0), $getFixedValue],
            [2, 1, new FixedValueFunction(1), $getFixedValue],
            [-1, -1, new FixedValueFunction(0), $getFixedValue],

            [1, 1, new FixedMicrotimeFunction(0), $getMicrotime],
            [-1, -1, new FixedMicrotimeFunction(0), $getMicrotime],
            [2, 1, new FixedMicrotimeFunction(1), $getMicrotime],

            [1, 1, new FixedDateFunction(0), $getDate],
            [-1, -1, new FixedDateFunction(0), $getDate],
            [2, 1, new FixedDateFunction(1), $getDate],

            [
                1.00000001,
                0.00000001,
                new FixedMicrotimeFunction(1),
                $getMicrotime
            ],
            [
                1.00000009,
                0.00000009,
                new FixedMicrotimeFunction(1),
                $getMicrotime
            ],
        ];
    }
}
