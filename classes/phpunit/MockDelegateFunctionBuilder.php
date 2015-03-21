<?php

namespace malkusch\phpmock\phpunit;

use malkusch\phpmock\ParameterBuilder;

/**
 * Defines a MockDelegateFunction.
 *
 * @author Markus Malkusch <markus@malkusch.de>
 * @link bitcoin:1335STSwu9hST4vcMRppEPgENMHD2r1REK Donations
 * @license http://www.wtfpl.net/txt/copying/ WTFPL
 * @internal
 */
class MockDelegateFunctionBuilder
{
    
    /**
     * @var int The instance counter.
     */
    private static $counter = 0;
    
    /**
     *
     * @var string The namespace of the build class.
     */
    private $namespace;
    
    /**
     * @var \Text_Template The MockDelegateFunction template.
     */
    private $template;
    
    /**
     * Instantiation.
     */
    public function __construct()
    {
        $this->template = new \Text_Template(__DIR__ . "/MockDelegateFunction.tpl");
    }
    
    /**
     * Builds a MockDelegateFunction for a function.
     *
     * @param string|null $functionName The mocked function.
     *
     * @SuppressWarnings(PHPMD)
     */
    public function build($functionName = null)
    {
        self::$counter++;
        
        $this->namespace = __NAMESPACE__ . self::$counter;
        
        $parameterBuilder = new ParameterBuilder();
        $parameterBuilder->build($functionName);
        
        $data = [
            "namespace" => $this->namespace,
            "signatureParameters" => $parameterBuilder->getSignatureParameters(),
        ];
        $this->template->setVar($data, false);
        $definition = $this->template->render();
        
        eval($definition);
    }

    /**
     * Returns the fully qualified class name
     *
     * @return string The class name.
     */
    public function getFullyQualifiedClassName()
    {
        return "$this->namespace\\MockDelegateFunction";
    }
}
