<?php

namespace Graft\Framework\Annotation;

use Graft\Framework\Common\AbstractAnnotation;
use Graft\Container\Component\Action as WPAction;
use Doctrine\Common\Annotations\Annotation;
use \ReflectionMethod;

/**
 * WordPress Action Annotation
 * Hook into Action
 * 
 * @final
 * 
 * @Annotation()
 * @Target("METHOD")
 * 
 * @package  GraftFramework
 * @category Annotation
 * @author   Zusoy <gregoire.drapeau79@gmail.com>
 * @license  MIT
 * @since    0.0.4
 */
final class Action extends AbstractAnnotation
{
    /**
     * Action Name
     * 
     * @Required()
     *
     * @var string
     */
    public $name;

    /**
     * Action Priority
     *
     * @var integer
     */
    public $priority;

    /**
     * Action Accepted Params
     *
     * @var integer
     */
    public $params;


    /**
     * Action Annotation Constructor
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
        $actionComponent = new WPAction();

        $location = $this->class->getFileName();
        $actionComponent->setTag($this->name)
            ->setAcceptedParams($this->params)
            ->setPriority($this->priority)
            ->setDefinitionLocation($location)
            ->setCallback([$this->instance, $this->method->getName()]);

        //add component to application container
        $this->container->addUsedWordPressComponent($actionComponent);

        //hook to WordPress Action
        $actionComponent->hook();
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