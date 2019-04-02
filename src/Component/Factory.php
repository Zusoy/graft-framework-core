<?php

namespace Graft\Framework\Component;

use \ReflectionClass;
use \ReflectionMethod;
use \ReflectionProperty;
use Graft\Framework\ObjectReference;
use Graft\Framework\Component\Container;
use Graft\Framework\Common\AbstractAnnotation;
use Graft\Framework\Definition\FactoryInterface;
use Graft\Framework\Exception\AnnotationTransgressedExclusion;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use HaydenPierce\ClassFinder\ClassFinder;

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
        $this->prepareAnnotations();
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

        //get all components
        foreach ($classes as $class)
        {
            $reference = new ObjectReference(new $class());
            $this->container->addObjectReference($reference);
        }

        //read all components annotations
        foreach ($this->container->getObjectReferences() as $reference)
        {
            $this->readAnnotations(
                $reference->getReflection(),
                $reference->getInstance()
            );
        }

        return $this->container;
    }


    /**
     * Read Class Annotations
     *
     * @param ReflectionClass $class    Class to Read
     * @param object          $instance Object Instance
     * 
     * @return void
     */
    public function readAnnotations(ReflectionClass $class, object $instance)
    {
        $methods = $class->getMethods();
        $properties = $class->getProperties();
        $classAnnotations = $this->reader->getClassAnnotations($class);

        //handle class annotations
        foreach ($classAnnotations as $cAnnotation)
        {
            if ($cAnnotation instanceof AbstractAnnotation)
            {
                $this->handleClassAbstractAnnotation(
                    $class,
                    $cAnnotation,
                    $instance
                );
            }
        }

        //handle methods annotations
        foreach ($methods as $method)
        {
            $methodAnnotations = $this->reader->getMethodAnnotations($method);

            foreach ($methodAnnotations as $mAnnotation)
            {
                if ($mAnnotation instanceof AbstractAnnotation)
                {
                    $this->handleMethodAbstractAnnotation(
                        $method, 
                        $mAnnotation, 
                        $instance
                    );
                }
            }
        }

        //handle properties annotations
        foreach ($properties as $property)
        {
            $propertyAnnotations = $this->reader->getPropertyAnnotations($property);

            foreach ($propertyAnnotations as $pAnnotation)
            {
                if ($pAnnotation instanceof AbstractAnnotation)
                {
                    $this->handlePropertyAbstractAnnotation(
                        $property,
                        $pAnnotation,
                        $instance
                    );
                }
            }
        }
    }


    /**
     * Handle Class Abstract Annotation
     * 
     * @throws AnnotationTransgressedExclusion
     *
     * @param ReflectionMethod   $method     Current Method
     * @param AbstractAnnotation $annotation Annotation to Handle
     * @param object             $instance   Object Instance
     * 
     * @return void
     */
    protected function handleClassAbstractAnnotation(
        ReflectionClass $class,
        AbstractAnnotation $annotation,
        object $instance
    ) {
        $annotationExclusions = $annotation->getReflectionExclusions();

        //check annotation class exclusion
        foreach ($annotationExclusions as $exclusion)
        {
            if ($exclusion === ReflectionClass::IS_EXPLICIT_ABSTRACT && $class->isAbstract())
            {
                throw new AnnotationTransgressedExclusion(
                    "Transgressed 'IS_EXPLICIT_ABSTRACT' Annotation Exclusion in Class '".
                    $class->getShortName()."' from '".$class->getFileName()."'"
                );
            }
            else if ($exclusion === ReflectionClass::IS_FINAL && $class->isFinal())
            {
                throw new AnnotationTransgressedExclusion(
                    "Transgressed 'IS_FINAL' Annotation Exclusion in Class '".
                    $class->getShortName()."' from '".$class->getFileName()."'"
                );
            }
        }

        $annotation->setClass($class)
            ->setMethod(null)
            ->setProperty(null)
            ->setInstance($instance)
            ->setContainer($this->container);

        $annotation->action();
    }


    /**
     * Handle Method Abstract Annotation
     * 
     * @throws AnnotationTransgressedExclusion
     *
     * @param ReflectionMethod   $method     Current Method
     * @param AbstractAnnotation $annotation Annotation to Handle
     * @param object             $instance   Object Instance
     * 
     * @return void
     */
    protected function handleMethodAbstractAnnotation(
        ReflectionMethod $method, 
        AbstractAnnotation $annotation,
        object $instance
    ) {
        $annotationExclusions = $annotation->getReflectionExclusions();

        //check annotation method exclusion
        foreach ($annotationExclusions as $exclusion)
        {
            if ($exclusion === ReflectionMethod::IS_STATIC && $method->isStatic())
            {
                throw new AnnotationTransgressedExclusion(
                    "Transgressed 'IS_STATIC' Annotation Exclusion in Method '".
                    $method->getName()."' from '".$method->getDeclaringClass()->getFileName()."'"
                );
            }
            else if ($exclusion === ReflectionMethod::IS_PUBLIC && $method->isPublic())
            {
                throw new AnnotationTransgressedExclusion(
                    "Transgressed 'IS_PUBLIC' Annotation Exclusion in Method '".
                    $method->getName()."' from '".$method->getDeclaringClass()->getFileName()."'"
                );
            }
            else if ($exclusion === ReflectionMethod::IS_PROTECTED && $method->isProtected())
            {
                throw new AnnotationTransgressedExclusion(
                    "Transgressed 'IS_PROTECTED' Annotation Exclusion in Method '".
                    $method->getName()."' from '".$method->getDeclaringClass()->getFileName()."'"
                );
            }
            else if ($exclusion === ReflectionMethod::IS_PRIVATE && $method->isPrivate())
            {
                throw new AnnotationTransgressedExclusion(
                    "Transgressed 'IS_PRIVATE' Annotation Exclusion in Method '".
                    $method->getName()."' from '".$method->getDeclaringClass()->getFileName()."'"
                );
            }
            else if ($exclusion === ReflectionMethod::IS_ABSTRACT && $method->isAbstract())
            {
                throw new AnnotationTransgressedExclusion(
                    "Transgressed 'IS_ABSTRACT' Annotation Exclusion in Method '".
                    $method->getName()."' from '".$method->getDeclaringClass()->getFileName()."'"
                );
            }
            else if ($exclusion === ReflectionMethod::IS_FINAL && $method->isFinal())
            {
                throw new AnnotationTransgressedExclusion(
                    "Transgressed 'IS_FINAL' Annotation Exclusion in Method '".
                    $method->getName()."' from '".$method->getDeclaringClass()->getFileName()."'"
                );
            }
        }

        $annotation->setClass($method->getDeclaringClass())
            ->setMethod($method)
            ->setProperty(null)
            ->setInstance($instance)
            ->setContainer($this->container);
        
        $annotation->action();
    }

    
    /**
     * Handle Property Abstract Annotation
     * 
     * @throws AnnotationTransgressedExclusion
     *
     * @param ReflectionProperty $property   Current Property
     * @param AbstractAnnotation $annotation Annotation to Handle
     * @param object             $instance   Object Instance
     * 
     * @return void
     */
    protected function handlePropertyAbstractAnnotation(
        ReflectionProperty $property,
        AbstractAnnotation $annotation,
        object $instance
    ) {
        $annotationExclusions = $annotation->getReflectionExclusions();

        //check annotation property exclusion
        foreach ($annotationExclusions as $exclusion)
        {
            if ($exclusion === ReflectionProperty::IS_STATIC && $property->isStatic())
            {
                throw new AnnotationTransgressedExclusion(
                    "Transgressed 'IS_STATIC' Annotation Exclusion in Property '".
                    $property->getName()."' from '".$property->getDeclaringClass()->getFileName()."'"
                );
            }
            else if ($exclusion === ReflectionProperty::IS_PUBLIC && $property->isPublic())
            {
                throw new AnnotationTransgressedExclusion(
                    "Transgressed 'IS_PUBLIC' Annotation Exclusion in Property '".
                    $property->getName()."' from '".$property->getDeclaringClass()->getFileName()."'"
                );
            }
            else if ($exclusion === ReflectionProperty::IS_PROTECTED && $property->isProtected())
            {
                throw new AnnotationTransgressedExclusion(
                    "Transgressed 'IS_PROTECTED' Annotation Exclusion in Property '".
                    $property->getName()."' from '".$property->getDeclaringClass()->getFileName()."'"
                );
            }
            else if ($exclusion === ReflectionProperty::IS_PRIVATE && $property->isPrivate())
            {
                throw new AnnotationTransgressedExclusion(
                    "Transgressed 'IS_PRIVATE' Annotation Exclusion in Property '".
                    $property->getName()."' from '".$property->getDeclaringClass()->getFileName()."'"
                );
            }
        }

        $annotation->setClass($property->getDeclaringClass())
            ->setMethod(null)
            ->setProperty($property)
            ->setInstance($instance)
            ->setContainer($this->container);
        
        $annotation->action();
    }


    /**
     * Prepare Annotations System
     *
     * @return void
     */
    private function prepareAnnotations()
    {
        AnnotationRegistry::registerLoader('class_exists');
        $this->reader = new AnnotationReader();
    }
}