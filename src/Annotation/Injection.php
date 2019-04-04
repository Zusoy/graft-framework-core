<?php

namespace Graft\Framework\Annotation;

use Graft\Framework\Common\AbstractAnnotation;
use Doctrine\Common\Annotations\Annotation;
use \ReflectionMethod;

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
     * Annotation Action
     *
     * @return void
     */
    public function action()
    {
        //injection not supported on Constructor
        if ($this->method->isConstructor()) {
            return;
        }

        $parameters = $this->method->getParameters();
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