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
        $name = $this->mock->getName();
        $canonicalFunctionName = $this->mock->getCanonicalFunctionName();
        
        $definition = "
            namespace {$this->mock->getNamespace()};
                
            use malkusch\phpmock\MockFunctionHelper;

            function $name({$this->getParametersList(true)})
            {
                \$arguments = [{$this->getParametersList(false)}];
                return MockFunctionHelper::call(
                    '$name',
                    '$canonicalFunctionName',
                    \$arguments
                );
            }";
        eval($definition);
    }

    /**
     * Get a list of parameters for the function-definition
     *
     * @param bool $signature If the list is the signature definition.
     *
     * @return string
     */
    private function getParametersList($signature)
    {
        $functionReflection = new \ReflectionFunction($this->mock->getName());
        $argsReflection = $functionReflection->getParameters();
        $arguments = array();

        foreach ($argsReflection as $arg) {
            if ($arg->name == "...") {
                // If '...' is set as name of a parameter this is a variadic
                // C-implementation before PHP5.5 - There is no way of knowing
                // how many parameters there might be given, so lets simply
                // use the func_get_args for getting the params. As these
                // "variadic" functions do not use pas-by-reference it doesn't
                // matter.
                return $signature ? "" : "func_get_args()";
            }
            $argument = "";
            if (true === $arg->isPassedByReference()) {
                $argument .= "&";
            }
            $argument .= '$' . $arg->name;

            if ($signature && $arg->isOptional()) {
                $argument .= " = '" . self::DEFAULT_ARGUMENT . "'";
            }
            $arguments[] = $argument;
        }

        return implode(", ", $arguments);
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
