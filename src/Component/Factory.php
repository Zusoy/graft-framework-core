<?php

namespace Graft\Framework\Component;

use HaydenPierce\ClassFinder\ClassFinder;
use Doctrine\Common\Annotations\AnnotationReader;
use Graft\Framework\Component\Container;
use Graft\Framework\ObjectReference;
use Graft\Framework\Definition\FactoryInterface;
use Graft\Framework\Common\AbstractAnnotation;
use \ReflectionClass;
use \ReflectionMethod;

/**
 * Factory Component
 * 
 * @package  Graft/Component
 * @category Component
 * @author   Zusoy <gregoire.drapeau79@gmail.com>
 * @license  MIT
 * @since    0.0.3
 */
class Factory implements FactoryInterface
{
    /**
     * Factory Handling Namespace
     *
     * @var string
     */
    protected $namespace;

    /**
     * Container in Construction
     *
     * @var Container
     */
    protected $container;

    /**
     * Annotations Reader
     * 
     * @var AnnotationReader
     */
    protected $reader;


    /**
     * Factory Constructor
     * 
     * @final
     * 
     * @param string $namespace Namespace to build
     */
    final public function __construct(string $namespace)
    {
        $this->namespace = $namespace;
        $this->reader = new AnnotationReader();
    }


    /**
     * Build Application Container
     * 
     * @param Container $container Application Container
     *
     * @return Container
     */
    public function build(Container $container)
    {
        $this->container = $container;

        //get Application classes with Namespace
        $classes = ClassFinder::getClassesInNamespace(
            $this->namespace,
            ClassFinder::RECURSIVE_MODE
        );

        foreach ($classes as $class)
        {
            $reference = new ObjectReference(new $class());
            $this->readAnnotations($reference->getReflection());
            $this->container->addObjectReference($reference);
        }

        return $this->container;
    }


    /**
     * Read Class Annotations
     *
     * @param ReflectionClass $class Class to Read
     * 
     * @return void
     */
    public function readAnnotations(ReflectionClass $class)
    {
        $methods = $class->getMethods();

        foreach ($methods as $method)
        {
            $methodAnnotations = $this->reader->getMethodAnnotations($method);
        }
    }
}