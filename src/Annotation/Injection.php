<?php

namespace Graft\Framework\Annotation;

use Graft\Framework\Common\AbstractAnnotation;
use Doctrine\Common\Annotations\Annotation;
use Graft\Container\Resolver\ParameterValueResolver;
use \ReflectionMethod;
use \Exception;

/**
 * Injection Annotation
 * Handle Component Injection
 * 
 * @final
 * 
 * @Annotation()
 * @Target("METHOD")
 * 
 * @package  Graft/Annotation
 * @category Annotation
 * @author   Zusoy <gregoire.drapeau79@gmail.com>
 * @license  MIT
 * @since    0.0.6
 */
final class Injection extends AbstractAnnotation
{
    /**
     * Parameters Options
     * 
     * @Required()
     *
     * @var array
     */
    public $parameters;


    /**
     * Injection Annotation Constructor
     * 
     * @param array $values Annotation Parameters Values
     */
    public function __construct(array $values)
    {
        $this->parameters = $values['parameters'];
    }


    /**
     * Annotation Action
     *
     * @return void
     */
    public function action()
    {
        $resolver = new ParameterValueResolver();
        $methodParameters = $this->method->getParameters();
        $injectParameters = [];

        foreach ($methodParameters as $methodParam) {
            $name = $methodParam->getName();
            $injectionParam = (isset($this->parameters[$name]))
                ? $this->parameters[$name]
                : null;
            
            if ($injectionParam !== null) {
                //try to resolve Container Parameter
                $containerParam = $resolver->resolveParameter(
                    $injectionParam,
                    $this->container
                );

                $newParam = ($containerParam !== null)
                    ? $containerParam->getValue()
                    : $injectionParam;

                $injectParameters[] = $newParam;
            }
        }

        //inject parameters into method
        if (\count($injectParameters) > 0) {
            $this->instance->{$this->method->getName()}(...$injectParameters);
        }
    }


    /**
     * Get Annotation Reflection Exclusions
     * Return Array with Reflections Types Constantes
     * 
     * Exclude Private and Protected Method to prevent accessibility error
     * 
     * @see https://www.php.net/manual/fr/class.reflectionmethod.php
     * 
     * @return int[]
     */
    public function getReflectionExclusions()
    {
        return [
            ReflectionMethod::IS_ABSTRACT,
            ReflectionMethod::IS_PROTECTED,
            ReflectionMethod::IS_PRIVATE
        ];
    }
}