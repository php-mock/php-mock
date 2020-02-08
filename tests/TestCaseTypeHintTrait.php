<?php

namespace phpmock;

/**
 * @internal
 */
trait TestCaseTypeHintTrait
{
    protected function setUp(): void
    {
        if (method_exists($this, 'setUpCompat')) {
            $this->setUpCompat();
        }
    }

    protected function tearDown(): void
    {
        if (method_exists($this, 'tearDownCompat')) {
            $this->tearDownCompat();
        }
    }
}
