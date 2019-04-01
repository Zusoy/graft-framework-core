<?php

namespace Graft\Framework\Annotation;

use Graft\Framework\Component\Container;
use Graft\Framework\Common\AbstractAnnotation;
use Doctrine\Common\Annotations\Annotation;
use Graft\Container\WPHook;
use Graft\Container\Component\Action as WPAction;
use \ReflectionClass;
use \ReflectionMethod;

/**
 * WordPress Action Annotation
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
class Action extends AbstractAnnotation
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
        $hookid = strtolower($this->name . ":" . $this->method->getName());

        $hookComponent = new WPAction();
        $hookComponent->setTag($this->name)
            ->setPriority($this->priority)
            ->setCallback([$this->instance, $this->method])
            ->setAcceptedParams($this->params);

        //set Hook Definition Location as Class::methodName
        $hookComponent->setDefinitionLocation(
            $this->class->getName() . "::" . $this->method->getName()
        );

        //add Hook Component in Current Application Container
        $this->container->register($hookid, $hookComponent);

        //hook to WordPress Action
        \add_action(
            $this->name,
            [$this->instance, $this->method->getName()],
            $this->priority,
            $this->params
        );
    }


    /**
     * Get Annotation Reflection Exclusions
     * Return Array with Reflections Types Constantes
     * 
     * Exclude Private and Protected Method to prevent accessibility error
     * 
     * @see https://www.php.net/manual/fr/class.reflectionmethod.php
     * 
     * @return int[]|array
     */
    public function getReflectionExclusions()
    {
        return [
            ReflectionMethod::IS_PRIVATE,
            ReflectionMethod::IS_PROTECTED
        ];
    }
}