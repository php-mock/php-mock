<?php

namespace malkusch\phpmock;

/**
 * Mocking framework for built-in PHP functions.
 * 
 * Mocking a build-in PHP function is achieved by using
 * PHP's namespace fallback policy. A mock will provide the namespaced function.
 * I.e. only unqualified functions in a non-global namespace can be mocked.
 *
 * @author Markus Malkusch <markus@malkusch.de>
 * @link bitcoin:1335STSwu9hST4vcMRppEPgENMHD2r1REK Donations
 * @license WTFPL
 * @see MockBuilder
 */
class Mock
{
    
    /**
     * @var callable[] defined callables for the mocks.
     */
    private static $functions = array();
    
    /**
     * @var string namespace for the mock function.
     */
    private $namespace;
    
    /**
     * @var string function name of the mocked function.
     */
    private $name;
    
    /**
     * @var callable The function mock.
     */
    private $function;
    
    /**
     * Set the namespace, function name and the mock function.
     * 
     * @param string   $namespace  The namespace for the mock function.
     * @param string   $name       The function name of the mocked function.
     * @param callable $function   The mock function.
     */
    public function __construct($namespace, $name, callable $function)
    {
        $this->namespace = $namespace;
        $this->name = $name;
        $this->function = $function;
    }
    
    /**
     * Enables this mock.
     */
    public function enable()
    {
        $this->defineMockFunction();

        self::$functions[$this->getCanonicalFunctionName()] = $this->function;
    }

    /**
     * Disable this mock.
     */
    public function disable()
    {
        unset(self::$functions[$this->getCanonicalFunctionName()]);
    }
    
    /**
     * Returns the defined callable for a mocked function.
     * 
     * This method is called from the function mock.
     * 
     * @param string $canonicalFunctionName The canonical function name.
     * @return callable
     * @internal
     */
    public static function getCallable($canonicalFunctionName)
    {
        if (!isset(self::$functions[$canonicalFunctionName])) {
            return null;
            
        }
        return self::$functions[$canonicalFunctionName];
    }
    
    /**
     * Returns the function name with its namespace.
     * 
     * @return String The function name with its namespace.
     */
    private function getCanonicalFunctionName()
    {
        return "$this->namespace\\$this->name";
    }

    /**
     * Defines the mocked function in the given namespace.
     * 
     * If the function was already defined this method does nothing.
     */
    private function defineMockFunction()
    {
        $canonicalFunctionName = $this->getCanonicalFunctionName();
        if (function_exists($canonicalFunctionName)) {
            return;
            
        }
        
        $definition = "
            namespace $this->namespace {
                function $this->name()
                {
                    \$callable = \malkusch\phpmock\Mock::getCallable(
                            '$canonicalFunctionName'
                    );
                    if (empty(\$callable)) {
                        \$callable = '$this->name';
                        
                    }
                    return call_user_func_array(\$callable, func_get_args());
                }
            }";
                
        eval($definition);
    }
}
