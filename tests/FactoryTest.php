<?php
require __DIR__ . "/../../../../../wordpress/wp-load.php";

use PHPUnit\Framework\TestCase;
use Graft\Test\Fake\Controller\HookController;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Graft\Framework\Annotation\Filter;
use Graft\Framework\Annotation\Action;

/**
 * Test Factory and Build Behaviours
 * 
 * @category Test
 */
class FactoryTest extends TestCase
{
    /**
     * Core Plugin
     *
     * @var GraftCorePlugin
     */
    private $corePlugin;


    /**
     * FactoryTest Constructor
     */
    public function __construct()
    {
        require_once dirname(__DIR__) . "/index.php";
        $this->corePlugin = new GraftCorePlugin();
    }


    /**
     * Test Factory Hook Annotations Reading
     * Test if Plugin Hooks component are the same as Annotations in Controller
     *
     * @return void
     */
    public function testHookAnnotationsReading()
    {   
        //read controller's hook annotations
        AnnotationRegistry::registerLoader('class_exists');
        $reader = new AnnotationReader();
        $hookControllerReflection = new ReflectionClass(HookController::class);
        $controllerAnnotations = [];
        
        foreach ($hookControllerReflection->getMethods() as $method)
        {
            $methodAnnotations = $reader->getMethodAnnotations($method);
            foreach ($methodAnnotations as $methodAnnotation) {
                $controllerAnnotations[] = $methodAnnotation;
            }
        }
        $controllerAnnotations = \array_filter($controllerAnnotations, function($annotation){
            return ($annotation instanceof Filter || $annotation instanceof Action);
        });

        //check if hooks component in container correspond to controller's hook annotations
        foreach ($controllerAnnotations as $key => $controllerAnnotation)
        {
            $containerComponent = $this->corePlugin->getContainer()->getHookComponents()[$key];
            
            $firstAssert = ($containerComponent->getTag() === $controllerAnnotation->name);
            $secondAssert = ($containerComponent->getPriority() === $controllerAnnotation->priority);
            $thirdAssert = ($containerComponent->getAcceptedParams() === $controllerAnnotation->params);

            $this->assertTrue($firstAssert && $secondAssert && $thirdAssert);
        }
    }
}
