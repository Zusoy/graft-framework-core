<?php

namespace Graft\Framework\Definition;

use Graft\Framework\Component\Container;
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
     * @param Container $container Container to Build
     *
     * @return Container
     */
    public function build(Container $container);

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