<?php

namespace phpmock;

use phpmock\functions\FixedValueFunction;
use PHPUnit\Framework\TestCase;

/**
 * Tests the ordering of the mock creation.
 *
 * @author Markus Malkusch <markus@malkusch.de>
 * @link bitcoin:1335STSwu9hST4vcMRppEPgENMHD2r1REK Donations
 * @license http://www.wtfpl.net/txt/copying/ WTFPL
 * @see Mock
 */
class MockDefiningOrderTest extends TestCase
{
    use TestCaseTrait;

    /**
     * @var Mock The mock.
     */
    private $mock;

    protected function tearDownCompat()
    {
        if (isset($this->mock)) {
            $this->mock->disable();
        }
    }

    /**
     * Returns the built-in call to escapeshellcmd().
     *
     * @param string $command Shell command.
     *
     * @return string The built-in call.
     */
    private static function escapeshellcmd($command)
    {
        return escapeshellcmd($command);
    }

    /**
     * Tests the restriction of Bug #68541.
     *
     * The fallback policy seems to be static for called class methods. This
     * is documented in Bug #68541. The mock function has to be defined before
     * the first call in a class.
     *
     * When this bug fails, PHP behaviour changed its behaviour and the
     * documentation can be updated.
     *
     * @link https://bugs.php.net/bug.php?id=68541 Bug #68541
     */
    public function testDefineBeforeFirstCallRestriction()
    {
        /*
         * HHVM did fix this bug already.
         *
         * See https://github.com/sebastianbergmann/phpunit/issues/1356
         * for a better syntax.
         */
        if (defined('HHVM_VERSION')) {
            $this->markTestSkipped();
        }

        $function = __NAMESPACE__ . '\escapeshellcmd';
        $this->assertFalse(function_exists($function));

        self::escapeshellcmd("foo");

        $builder = new MockBuilder();
        $builder->setNamespace(__NAMESPACE__)
                ->setName("escapeshellcmd")
                ->setFunctionProvider(new FixedValueFunction("foo"));

        $this->mock = $builder->build();
        $this->mock->enable();

        $this->assertTrue(function_exists($function));
        $this->assertEquals("foo", escapeshellcmd("bar"));
        $this->assertEquals("bar", self::escapeshellcmd("bar"));
    }

    /**
     * Tests defining the mock after calling the unqualified function name.
     */
    public function testDefiningAfterCallingUnqualified()
    {
        $function = __NAMESPACE__ . '\highlight_string';
        $this->assertFalse(function_exists($function));
        highlight_string("foo", true);

        $builder = new MockBuilder();
        $builder->setNamespace(__NAMESPACE__)
                ->setName("highlight_string")
                ->setFunctionProvider(new FixedValueFunction("bar"));

        $this->mock = $builder->build();
        $this->mock->enable();

        $this->assertTrue(function_exists($function));
        $this->assertEquals("bar", highlight_string("foo"));
    }

    /**
     * Tests defining the mock after calling the qualified function name.
     */
    public function testDefiningAfterCallingQualified()
    {
        $function = __NAMESPACE__ . '\str_word_count';
        $this->assertFalse(function_exists($function));
        \str_word_count("foo");

        $builder = new MockBuilder();
        $builder->setNamespace(__NAMESPACE__)
                ->setName("str_word_count")
                ->setFunctionProvider(new FixedValueFunction("bar"));

        $this->mock = $builder->build();
        $this->mock->enable();

        $this->assertTrue(function_exists($function));
        $this->assertEquals("bar", str_word_count("foo"));
    }
}
