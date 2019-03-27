<?php

namespace Graft\Framework\Common;

use Graft\Framework\Component\Container;
use \ReflectionClass;
use \ReflectionMethod;

/**
 * Common Annotation Class
 * All WordPress Annotation must extends this Class
 * 
 * @abstract
 * 
 * @package  Graft/Common
 * @category Common
 * @author   Zusoy <gregoire.drapeau79@gmail.com>
 * @license  MIT
 * @since    0.0.4
 */
abstract class AbstractAnnotation
{
    /**
     * Current Annotation Reflection Class
     *
     * @var ReflectionClass
     */
    protected $class;

    /**
     * Current Annotation Reflection Method
     *
     * @var ReflectionMethod|null
     */
    protected $method;

    /**
     * Current Annotation Class Instance
     *
     * @var object
     */
    protected $instance;

    /**
     * Current Application Container
     *
     * @var Container
     */
    protected $container;

    
    /**
     * Annotation Action
     * 
     * @abstract
     *
     * @return void
     */
    public abstract function action();


    /**
     * Set Current Annotation Reflection Class
     *
     * @param ReflectionClass $class Current Reflection Class
     * 
     * @return self
     */
    public function setClass(ReflectionClass $class)
    {
        $this->class = $class;

        return $this;
    }


    /**
     * Get Current Annotation Reflection Class
     *
     * @return ReflectionClass
     */
    public function getClass()
    {
        return $this->class;
    }


    /**
     * Set Current Annotation Reflection Method
     *
     * @param ReflectionMethod|null $method Current Reflection Method
     * 
     * @return self
     */
    public function setMethod(?ReflectionMethod $method)
    {
        $this->method = $method;

        return $this;
    }


    /**
     * Get Current Annotation Reflection Method
     *
     * @return ReflectionMethod|null
     */
    public function getMethod()
    {
        return $this->method;
    }


    /**
     * Set Current Annotation Class Instance
     *
     * @param object $instance Current Class Instance
     * 
     * @return self
     */
    public function setInstance(object $instance)
    {
        $this->instance = $instance;

        return $this;
    }


    /**
     * Get Current Annotation Class Instance
     *
     * @return object
     */
    public function getInstance()
    {
        return $this->instance;
    }


    /**
     * Set Current Application Container
     *
     * @param Container $container Current Application Container
     * 
     * @return self
     */
    public function setContainer(Container $container)
    {
        $this->container = $container;

        return $this;
    }


    /**
     * Get Current Application Container
     *
     * @return Container
     */
    public function getContainer()
    {
        return $this->container;
    }
}