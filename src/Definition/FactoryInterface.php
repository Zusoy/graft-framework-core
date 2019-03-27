<?php

namespace Graft\Framework\Definition;

use Graft\Framework\Component\Container;

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
}