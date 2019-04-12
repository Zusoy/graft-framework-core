<?php

namespace Graft\Framework\Annotation;

use Graft\Framework\Common\AbstractAnnotation;
use Doctrine\Common\Annotations\Annotation;
use Graft\Container\Component\Filter as WPFilter;
use \ReflectionMethod;

/**
 * WordPress Filter Annotation
 * Hook into Filter
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
 * @since    0.0.4
 */
final class Filter extends AbstractAnnotation
{
    /**
     * Filter Name
     * 
     * @Required()
     *
     * @var string
     */
    public $name;

    /**
     * Filter Priority
     *
     * @var integer
     */
    public $priority;

    /**
     * Filter Accepted Params
     *
     * @var integer
     */
    public $params;


    /**
     * Filter Annotation Constructor
     * 
     * @param array $values Annotation Parameters Values
     */
    public function __construct(array $values)
    {
        $this->name = $values['name'];

        $this->priority = (isset($values['priority']))
            ? $values['priority']
            : 10;
        
        $this->params = (isset($values['params']))
            ? $values['params']
            : 1;
    }


    /**
     * Annotation Action
     *
     * @return void
     */
    public function action()
    {
        $filterComponent = new WPFilter();
        
        $location = $this->class->getFileName();
        $filterComponent->setTag($this->name)
            ->setAcceptedParams($this->params)
            ->setPriority($this->priority)
            ->setDefinitionLocation($location)
            ->setCallback([$this->instance, $this->method->getName()]);
        
        //add component to application container
        $this->container->addWordPressComponent($filterComponent);

        //hook to WordPress Filter
        $filterComponent->hook();
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
            ReflectionMethod::IS_PRIVATE,
            ReflectionMethod::IS_PROTECTED
        ];
    }
}