<?php

namespace Graft\Framework\Annotation;

use Graft\Framework\Common\AbstractAnnotation;
use Graft\Container\Component\AdminMenu as WPAdminMenu;
use Graft\Framework\Plugin;
use Doctrine\Common\Annotations\Annotation;
use \ReflectionMethod;

/**
 * WordPress Administration Menu Annotation
 * Create Administration Menu
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
final class AdminMenu extends AbstractAnnotation
{
    /**
     * AdminMenu Title
     * 
     * @Required()
     *
     * @var string
     */
    public $title;

    /**
     * AdminMenu Capability
     *
     * @var string
     */
    public $capability;

    /**
     * AdminMenu Parent Slug
     *
     * @var string
     */
    public $parent;

    /**
     * AdminMenu Icon Path
     *
     * @var string
     */
    public $icon;

    /**
     * AdminMenu Position
     * 
     * @var integer
     */
    public $position;


    /**
     * AdminMenu Annotation Constructor
     *
     * @param array $values Annotation Parameters Values
     */
    public function __construct(array $values)
    {
        $this->title = $values['title'];

        $this->capability = (isset($values['capability']))
            ? $values['capability']
            : Plugin::getCurrent()->getConfigNode("application", "capability");
        
        $this->parent = (isset($values['parent']))
            ? $values['parent']
            : null;
        
        $this->icon = (isset($values['icon']))
            ? $values['icon']
            : null;
        
        $this->position = (isset($values['position']))
            ? $values['position']
            : null;
    }


    /**
     * Annotation Action
     *
     * @return void
     */
    public function action()
    {
        $componentid = strtolower("menu:".$this->title);

        //create the Menu
        $menuComponent = new WPAdminMenu(
            $this->title,
            [$this->instance, $this->method->getName()],
            $this->capability,
            $this->parent,
            $this->icon,
            $this->position
        );

        //add AdminMenu Component in Current Application Container
        $this->container->register($componentid, $menuComponent);
    }


    /**
     * Get Annotation Reflection Exclusions
     * Return Array with Reflections Types Constantes
     * 
     * Exclude Private and Protected Method to prevent accessibility error
     * 
     * @see https://www.php.net/manual/fr/class.reflectionmethod.php
     * 
     * @return int[]
     */
    public function getReflectionExclusions()
    {
        return [
            ReflectionMethod::IS_PRIVATE,
            ReflectionMethod::IS_PROTECTED
        ];
    }
}