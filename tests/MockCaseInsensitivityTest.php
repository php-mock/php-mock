<?php

namespace phpmock;

use phpmock\functions\FixedValueFunction;
use PHPUnit\Framework\TestCase;

/**
 * Tests Mock's case insensitivity.
 *
 * @author Markus Malkusch <markus@malkusch.de>
 * @link bitcoin:1335STSwu9hST4vcMRppEPgENMHD2r1REK Donations
 * @license http://www.wtfpl.net/txt/copying/ WTFPL
 * @see Mock
 */
class MockCaseInsensitivityTest extends TestCase
{
    use TestCaseTrait;

    /**
     * @var Mock
     */
    private $mock;

    protected function tearDownCompat()
    {
        if (isset($this->mock)) {
            $this->mock->disable();
        }
    }

    /**
     * @param string $mockName  The mock function name.
     *
     * @dataProvider provideTestCaseSensitivity
     * @test
     */
    public function testFailEnable($mockName)
    {
        $builder = new MockBuilder();
        $builder->setNamespace(__NAMESPACE__)
                ->setName(strtolower($mockName))
                ->setFunctionProvider(new FixedValueFunction(1234));

        $this->mock = $builder->build();
        $this->mock->enable();

        $failingMock = $builder->setName($mockName)->build();
        $this->expectException(MockEnabledException::class);
        $failingMock->enable();
    }

    /**
     * Tests case insensitive mocks.
     *
     * @param string $mockName  The mock function name.
     *
     * @test
     * @dataProvider provideTestCaseSensitivity
     */
    public function testCaseSensitivity($mockName)
    {
        $builder = new MockBuilder();
        $builder->setNamespace(__NAMESPACE__)
                ->setName($mockName)
                ->setFunctionProvider(new FixedValueFunction(1234));

        $this->mock = $builder->build();
        $this->mock->enable();

        $this->assertEquals(1234, time(), "time() is not mocked");
        $this->assertEquals(1234, Time(), "Time() is not mocked");
        $this->assertEquals(1234, TIME(), "TIME() is not mocked");
    }

    /**
     * Returns test cases for testCaseSensitivity().
     *
     * @return string[][] Test cases.
     */
    public function provideTestCaseSensitivity()
    {
        return [
            ["TIME"],
            ["Time"],
            ["time"],
        ];
    }
}
