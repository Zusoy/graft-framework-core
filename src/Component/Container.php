<?php

namespace Graft\Framework\Component;

use Graft\Container\WPContainer;
use Graft\Framework\ObjectReference;

/**
 * Application Container
 * 
 * @package  Graft/Component
 * @category Component
 * @author   Zusoy <gregoire.drapeau79@gmail.com>
 * @license  MIT
 * @since    0.0.3
 */
class Container extends WPContainer
{
    /**
     * Container Object References
     *
     * @var ObjectReference[]
     */
    protected $references = [];

    /**
     * Injectable Framework Components
     *
     * @var ObjectReference[]
     */
    protected $injectableComponents = [];


    /**
     * Get Injectable Framework Component or App Component by Class Name
     *
     * @param string $class Class Name
     * 
     * @return ObjectReference|null
     */
    public function getComponentByClassName(string $class)
    {
        $appComponent = $this->getObjectReferenceByClassName($class);

        if ($appComponent !== null) {
            return $appComponent;
        }

        $frameworkComponent = $this->getInjectableFrameworkComponentByClassName(
            $class
        );

        return ($frameworkComponent !== null)
            ? $frameworkComponent
            : null;
    }

    
    /**
     * Add Object Reference in Container
     *
     * @param ObjectReference $reference Object Reference
     * 
     * @return self
     */
    public function addObjectReference(ObjectReference $reference)
    {
        $this->references[] = $reference;

        return $this;
    }


    /**
     * Get Container References
     *
     * @return ObjectReference[]
     */
    public function getObjectReferences()
    {
        return $this->references;
    }


    /**
     * Get Container Reference by Class Name
     * 
     * @param string $className The Class Name to Find
     *
     * @return ObjectReference|null
     */
    public function getObjectReferenceByClassName(string $className)
    {
        foreach ($this->references as $reference)
        {
            if ($reference->getReflection()->getName() == $className)
            {
                return $reference;
            }
        }

        return null;
    }


    /**
     * Add Framework Injectable Component
     *
     * @param ObjectReference $component Framework Component
     * 
     * @return self
     */
    public function addInjectableFrameworkComponent(ObjectReference $component)
    {
        $this->injectableComponents[] = $component;

        return $this;
    }


    /**
     * Get Injectable Framework Components
     *
     * @return ObjectReference[]
     */
    public function getInjectableFrameworkComponents()
    {
        return $this->injectableComponents;
    }


    /**
     * Get Injectable Framework Component by Class name
     *
     * @param string $class Component Class Name
     * 
     * @return ObjectReference|null
     */
    public function getInjectableFrameworkComponentByClassName(string $class)
    {
        foreach ($this->injectableComponents as $component)
        {
            if ($component->getReflection()->getName() == $class)
            {
                return $component;
            }
        }

        return null;
    }
}