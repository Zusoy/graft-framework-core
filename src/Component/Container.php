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
}