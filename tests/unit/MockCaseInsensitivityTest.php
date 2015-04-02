<?php

namespace phpmock;

use phpmock\functions\FixedValueFunction;

/**
 * Tests Mock's case insensitivity.
 *
 * @author Markus Malkusch <markus@malkusch.de>
 * @link bitcoin:1335STSwu9hST4vcMRppEPgENMHD2r1REK Donations
 * @license http://www.wtfpl.net/txt/copying/ WTFPL
 * @see Mock
 */
class MockCaseInsensitivityTest extends \PHPUnit_Framework_TestCase
{
    
    /**
     * @var Mock
     */
    private $mock;
    
    protected function tearDown()
    {
        if (isset($this->mock)) {
            $this->mock->disable();
            
        }
    }

    /**
     * @param string $mockName  The mock function name.
     *
     * @expectedException phpmock\MockEnabledException
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
