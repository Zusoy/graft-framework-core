<?php

namespace Graft\Definition;

use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Configuration Handler Definition Interface
 * 
 * @package  Graft/Definition
 * @category Definition
 * @author   Zusoy <gregoire.drapeau79@gmail.com>
 * @license  MIT
 * @since    0.0.1
 */
interface ConfigurationHandlerInterface
{
    /**
     * Get Configuration File
     *
     * @return string
     */
    public function getFile();


    /**
     * Get Configuration Builder
     *
     * @return ConfigurationInterface
     */
    public function getBuilder();


    /**
     * Get Configuration Values
     *
     * @return array
     */
    public function getValues();


    /**
     * Process the Configuration
     *
     * @return void
     */
    public function processConfiguration();
}