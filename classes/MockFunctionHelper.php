<?php

namespace malkusch\phpmock;

/**
 * Helper which builds the mock function.
 *
 * @author Markus Malkusch <markus@malkusch.de>
 * @link bitcoin:1335STSwu9hST4vcMRppEPgENMHD2r1REK Donations
 * @license http://www.wtfpl.net/txt/copying/ WTFPL
 * @internal
 */
class MockFunctionHelper
{
    
    /**
     * @var string The internal name for optional parameters.
     */
    const DEFAULT_ARGUMENT = "optionalParameter";
 
    /**
     * @var Mock The mock.
     */
    private $mock;
    
    /**
     * Sets the mock.
     *
     * @param Mock $mock The mock.
     */
    public function __construct(Mock $mock)
    {
        $this->mock = $mock;
    }
    
    /**
     * Defines the mock function.
     */
    public function defineFunction()
    {
        $name                  = $this->mock->getName();
        $canonicalFunctionName = $this->mock->getCanonicalFunctionName();

        $parameterBuilder = new ParameterBuilder();
        $parameterBuilder->build($name);
        $signatureParameters = $parameterBuilder->getSignatureParameters();
        $bodyParameters      = $parameterBuilder->getBodyParameters();

        $definition = "
            namespace {$this->mock->getNamespace()};
                
            use malkusch\phpmock\MockFunctionHelper;

            function $name($signatureParameters)
            {
                \$arguments = [$bodyParameters];

                \$variadics = \\array_slice(\\func_get_args(), \\count(\$arguments));
                \$arguments = \\array_merge(\$arguments, \$variadics);

                return MockFunctionHelper::call(
                    '$name',
                    '$canonicalFunctionName',
                    \$arguments
                );
            }";
        eval($definition);
    }
    
    /**
     * Calls the enabled mock, or the built-in function otherwise.
     *
     * @param string $functionName          The function name.
     * @param string $canonicalFunctionName The canonical function name.
     * @param array  $arguments             The arguments.
     *
     * @return mixed The result of the called function.
     * @see Mock::define()
     */
    public static function call($functionName, $canonicalFunctionName, &$arguments)
    {
        $registry = MockRegistry::getInstance();
        $mock     = $registry->getMock($canonicalFunctionName);

        foreach ($arguments as $key => $argument) {
            if ($argument === self::DEFAULT_ARGUMENT) {
                unset($arguments[$key]);
            }
        }
        if (empty($mock)) {
            // call the built-in function if the mock was not enabled.
            return call_user_func_array($functionName, $arguments);
        
        } else {
            // call the mock function.
            return $mock->call($arguments);
        }
    }
}
