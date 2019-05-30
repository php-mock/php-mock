<?php

namespace phpmock;

/**
 * @internal
 */
trait TestCaseNoTypeHintTrait
{
    protected function setUp()
    {
        if (method_exists($this, 'setUpCompat')) {
            $this->setUpCompat();
        }
    }

    protected function tearDown()
    {
        if (method_exists($this, 'tearDownCompat')) {
            $this->tearDownCompat();
        }
    }
}
