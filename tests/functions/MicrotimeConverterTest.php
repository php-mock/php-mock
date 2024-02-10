<?php

namespace phpmock\functions;

use PHPUnit\Framework\TestCase;

/**
 * Tests MicrotimeConverter.
 *
 * @author Markus Malkusch <markus@malkusch.de>
 * @link bitcoin:1335STSwu9hST4vcMRppEPgENMHD2r1REK Donations
 * @license http://www.wtfpl.net/txt/copying/ WTFPL
 * @see MicrotimeConverter
 */
class MicrotimeConverterTest extends TestCase
{
    /**
     * Test convertStringToFloat().
     *
     * @param float  $float   The timestamp.
     * @param string $string  The timestamp.
     * @dataProvider provideFloatAndStrings
     */
    public function testConvertStringToFloat($float, $string)
    {
        $converter = new MicrotimeConverter();
        $this->assertEquals($float, $converter->convertStringToFloat($string));
    }

    /**
     * Test convertFloatToString().
     *
     * @param float  $float   The timestamp.
     * @param string $string  The timestamp.
     * @dataProvider provideFloatAndStrings
     */
    public function testConvertFloatToString($float, $string)
    {
        $converter = new MicrotimeConverter();
        $this->assertEquals($string, $converter->convertFloatToString($float));
    }

    /**
     * Returns convert test cases.
     *
     * @return array
     */
    public static function provideFloatAndStrings()
    {
        return [
            [1.0,        "0.00000000 1"],
            [1.00000001, "0.00000001 1"],
            [1.00000009, "0.00000009 1"],
            [1.1,        "0.10000000 1"],
            [1.11,       "0.11000000 1"],
            [1.9,        "0.90000000 1"],
            [1.99999999, "0.99999999 1"],
        ];
    }
}
