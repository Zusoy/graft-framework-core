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
interface ConfigurationHandlerInterface extends ConfigurationInterface
{
    /**
     * Get Configuration Name
     *
     * @return string
     */
    public function getConfigName();


    /**
     * Get Configuration File
     *
     * @return string
     */
    public function getConfigFile();
}