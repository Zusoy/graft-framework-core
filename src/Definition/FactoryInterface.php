<?php

namespace Graft\Framework\Definition;

use Graft\Container\WPContainer;
use \ReflectionClass;

/**
 * Factory Interface
 * 
 * @package  Graft/Definition
 * @category Definition
 * @author   Zusoy <gregoire.drapeau79@gmail.com>
 * @license  MIT
 * @since    0.0.3
 */
interface FactoryInterface
{
    /**
     * Build Application Container
     * 
     * @param WPContainer $container Container to Build
     *
     * @return WPContainer
     */
    public function build(WPContainer $container);

    /**
     * Read Class Annotations
     *
     * @param ReflectionClass $class    Class to Read
     * @param object          $instance Object Instance
     * 
     * @return void
     */
    public function readAnnotations(ReflectionClass $class, object $instance);
}