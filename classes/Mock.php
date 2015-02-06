<?php

namespace malkusch\phpmock;

use ReflectionFunction;

/**
 * Mocking framework for built-in PHP functions.
 *
 * Mocking a build-in PHP function is achieved by using
 * PHP's namespace fallback policy. A mock will provide the namespaced function.
 * I.e. only unqualified functions in a non-global namespace can be mocked.
 *
 * Example:
 * <code>
 * namespace foo;
 *
 * use malkusch\phpmock\Mock;
 *
 * $time = new Mock(
 *     __NAMESPACE__,
 *     "time",
 *     function () {
 *         return 3;
 *     }
 * );
 * $time->enable();
 * assert (3 == time());
 * </code>
 *
 * @author Markus Malkusch <markus@malkusch.de>
 * @link bitcoin:1335STSwu9hST4vcMRppEPgENMHD2r1REK Donations
 * @license http://www.wtfpl.net/txt/copying/ WTFPL
 * @see MockBuilder
 */
class Mock
{

    const DEFAULT_ARGUMENT = 'optionalParameter';
    
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
     * @var Recorder Call recorder.
     */
    private $recorder;
    
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
        $this->recorder = new Recorder();
    }
    
    /**
     * Returns the call recorder.
     *
     * Every call to the mocked function was recorded to this call recorder.
     *
     * @return Recorder The call recorder.
     */
    public function getRecorder()
    {
        return $this->recorder;
    }
    
    /**
     * Enables this mock.
     *
     * @throws MockEnabledException If the function has already an enabled mock.
     * @see Mock::disable()
     * @see Mock::disableAll()
     */
    public function enable()
    {
        $registry = MockRegistry::getInstance();
        if ($registry->isRegistered($this)) {
            throw new MockEnabledException(
                "$this->name is already enabled."
                . "Call disable() on the existing mock."
            );
            
        }
        $this->define();
        $registry->register($this);
    }

    /**
     * Disable this mock.
     *
     * @see Mock::enable()
     * @see Mock::disableAll()
     */
    public function disable()
    {
        MockRegistry::getInstance()->unregister($this);
    }
    
    /**
     * Disable all mocks.
     *
     * @see Mock::enable()
     * @see Mock::disable()
     */
    public static function disableAll()
    {
        MockRegistry::getInstance()->unregisterAll();
    }
    
    /**
     * Calls the mocked function.
     *
     * This method is called from the namespaced function.
     * It also records the call in the call recorder.
     *
     * @param array $arguments the call arguments.
     * @return mixed
     * @internal
     */
    public function call(array $arguments)
    {
        $this->recorder->record($arguments);
        
        $args = array();
        foreach ($arguments as $k => &$arg) {
            $args[$k] = &$arg;
        }
        
        return call_user_func_array($this->function, $args);
    }
    
    /**
     * Returns the function name with its namespace.
     *
     * @return String The function name with its namespace.
     * @internal
     */
    public function getCanonicalFunctionName()
    {
        return strtolower("{$this->getNamespace()}\\$this->name");
    }

    /**
     * Returns the namespace without enclosing slashes.
     *
     * @return string The namespace
     */
    private function getNamespace()
    {
        return trim($this->namespace, "\\");
    }
    
    /**
     * Defines the mocked function in the given namespace.
     *
     * In most cases you don't have to call this method. enable() is doing this
     * for you. But if the mock is defined after the first call in the
     * tested class, the tested class doesn't resolve to the mock. This is
     * documented in Bug #68541. You therefore have to define the namespaced
     * function before the first call. Defining the function has no side
     * effects as you still have to enable the mock. If the function was
     * already defined this method does nothing.
     *
     * @see enable()
     * @link https://bugs.php.net/bug.php?id=68541 Bug #68541
     */
    public function define()
    {
        $canonicalFunctionName = $this->getCanonicalFunctionName();
        if (function_exists($canonicalFunctionName)) {
            return;
            
        }
        
        $definition = "
            namespace {$this->getNamespace()};
                
            use malkusch\phpmock\MockCallHelper;

            function $this->name({$this->getParametersList()})
            {
                \$arguments = {$this->getArgumentsList()};
                return MockCallHelper::call(
                    '$this->name',
                    '$canonicalFunctionName',
                    \$arguments
                );
            }";

        eval($definition);
    }

    /**
     * Get a list of parameters for the function-definition
     *
     * @return string
     */
    private function getParametersList()
    {
        $functionReflection = new ReflectionFunction('\\' . $this->name);
        $argsReflection = $functionReflection->getParameters();
        $arguments = array();

        foreach ($argsReflection as $arg) {
            if ($arg->name == '...') {
                // If '...' is set as name of a parameter this is a variadic
                // C-implementation before PHP5.5 - There is no way of knowing
                // how many parameters there might be given, so lets simply
                // use the func_get_args for getting the params. As these
                // "variadic" functions do not use pas-by-reference it doesn't
                // matter.
                return '';
            }
            $argument = '';
            if (true === $arg->isPassedByReference()) {
                $argument .= '&';
            }
            $argument .= '$' . $arg->name;

            if ($arg->isOptional()) {
                $argument .= ' = \'' . self::DEFAULT_ARGUMENT . '\'';
            }
            $arguments[ ] = $argument;
        }

        return implode(', ', $arguments);
    }

    /**
     * Get a string representation of the params array
     *
     * @return string
     */
    private function getArgumentsList()
    {
        $functionReflection = new ReflectionFunction('\\' . $this->name);
        $argsReflection = $functionReflection->getParameters();
        $arguments = array();

        foreach ($argsReflection as $arg) {
            if ($arg->name == '...') {
                // If '...' is set as name of a parameter this is a variadic
                // C-implementation before PHP5.5 - There is no way of knowing
                // how many parameters there might be given, so lets simply
                // use the func_get_args for getting the params. As these
                // "variadic" functions do not use pas-by-reference it doesn't
                // matter.
                return 'func_get_args()';
            }

            if (true === $arg->isPassedByReference()) {
                $arguments[] = '&$' . $arg->name;
            } else {
                $arguments[] = '$' . $arg->name;
            }
        }

        return 'array(' . implode(', ', $arguments) . ')';
    }
}
