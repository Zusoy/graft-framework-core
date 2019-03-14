<?php

namespace Graft\Definition;

/**
 * Application Interface
 * 
 * @package  Graft/Definition
 * @category Definition
 * @author   Zusoy <gregoire.drapeau79@gmail.com>
 * @license  MIT
 * @since    0.0.1
 */
interface ApplicationInterface
{
    /**
     * Setup Application (Name, Description ...)
     *
     * @return void
     */
    public function setup();
}