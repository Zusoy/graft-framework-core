<?php

namespace Graft\Framework\Definition;

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
     * Start Building
     *
     * @return void
     */
    public function build();
}