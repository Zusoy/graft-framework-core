<?php

namespace Graft\Framework\Annotation;

use Graft\Framework\Common\AbstractAnnotation;
use Doctrine\Common\Annotations\Annotation;
use Graft\Container\Component\Filter as WPFilter;
use \ReflectionMethod;

/**
 * WordPress Filter Annotation
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
        $hookid = strtolower($this->name . ":" . $this->method->getName());

        $filterComponent = new WPFilter();
        $filterComponent->setTag($this->name)
            ->setPriority($this->priority)
            ->setCallback([$this->instance, $this->method])
            ->setAcceptedParams($this->params);
        
        //set Filter Definition Location as Class::methodName
        $filterComponent->setDefinitionLocation(
            $this->class->getName() . "::" . $this->method->getName()
        );

        //add Filter Component in Current Application Container
        $this->container->register($hookid, $filterComponent);

        //hook to WordPress Filter
        \add_filter(
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