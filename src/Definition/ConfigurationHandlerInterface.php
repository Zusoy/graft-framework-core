<?php

namespace Graft\Framework\Definition;

use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Configuration Handler Definition Interface
 * 
 * @package  GraftFramework
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
     * @param string $configDir Current Application Configuration Directory
     *
     * @return string
     */
    public function getConfigFile(string $configDir);
}