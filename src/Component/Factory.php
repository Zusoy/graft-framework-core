<?php

namespace Graft\Framework\Component;

use Graft\Framework\Common\AbstractAnnotation;
use Graft\Framework\Definition\FactoryInterface;
use Graft\Framework\Exception\AnnotationTransgressedExclusion;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use HaydenPierce\ClassFinder\ClassFinder;
use Graft\Container\WPContainer;
use \ReflectionClass;
use \ReflectionMethod;
use \ReflectionProperty;

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
     * @var WPContainer
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
     * @param WPContainer $container Application Container
     *
     * @return WPContainer
     */
    public function build(WPContainer $container)
    {
        $this->container = $container;

        $appClasses = ClassFinder::getClassesInNamespace(
            $this->namespace,
            ClassFinder::RECURSIVE_MODE
        );

        $frameworkClasses = ClassFinder::getClassesInNamespace(
            "Graft\\Framework\\Injectable",
            ClassFinder::RECURSIVE_MODE
        );

        //get injectables components from Graft Framework
        foreach ($frameworkClasses as $class) {
            $this->container->set($class, \DI\autowire($class));
        }

        //get application components
        foreach ($appClasses as $class) {
            $this->container->set($class, \DI\autowire($class));

            $reflection = new ReflectionClass($class);
            $instance = $this->container->get($class);
            $this->readAnnotations(
                $reflection,
                $instance
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