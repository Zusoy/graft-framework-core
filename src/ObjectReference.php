<?php

namespace Graft\Framework;

use \ReflectionClass;

/**
 * Object Reference Class for Application Container
 * 
 * @package  Graft
 * @category Framework
 * @author   Zusoy <gregoire.drapeau79@gmail.com>
 * @license  MIT
 * @since    0.0.3
 */
class ObjectReference
{
    /**
     * Object Instance
     *
     * @var object
     */
    protected $instance;

    /**
     * Object Class Reflection
     *
     * @var ReflectionClass
     */
    protected $reflection;


    /**
     * ObjectReference Constructor
     *
     * @param object|null $instance Object Instance to Refer (optional)
     */
    public function __construct(?object $instance = null)
    {
        if ($instance !== null) {
            $this->setInstance($instance);
            $this->setReflection(new ReflectionClass($instance));
        }
    }


    /**
     * Set Reference Instance
     *
     * @param object $instance Object Instance
     * 
     * @return self
     */
    public function setInstance(object $instance)
    {
        $this->instance = $instance;

        return $this;
    }


    /**
     * Get Reference Instance
     *
     * @return object
     */
    public function getInstance()
    {
        return $this->instance;
    }


    /**
     * Set Reference Reflection
     *
     * @param ReflectionClass $reflection Object Class Reflection
     * 
     * @return self
     */
    public function setReflection(ReflectionClass $reflection)
    {
        $this->reflection = $reflection;

        return $this;
    }


    /**
     * Get Reference Reflection
     *
     * @return ReflectionClass
     */
    public function getReflection()
    {
        return $this->reflection;
    }


    /**
     * Get Object Directory
     *
     * @return string
     */
    public function getDirectory()
    {
        return \dirname(
            $this->reflection->getFileName()
        );
    }
}