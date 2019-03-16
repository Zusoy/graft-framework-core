<?php

namespace Graft;

use \ComposerLocator;
use Graft\BasePluginConfigurationBuilder;
use Graft\Definition\ConfigurationHandlerInterface;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Default Configuration Handler for Plugin
 * 
 * @final
 * 
 * @package  Graft
 * @category BaseComponent
 * @author   Zusoy <gregoire.drapeau79@gmail.com>
 * @license  MIT
 * @since    0.0.1
 */
final class BasePluginConfigurationHandler implements ConfigurationHandlerInterface
{
    /**
     * Get Plugin Configuration File
     *
     * @return string
     */
    public function getFile()
    {
        return ComposerLocator::getRootPath() . "/config/plugin.yaml";
    }


    /**
     * Get Plugin Configuration Builder
     * 
     * @return ConfigurationInterface
     */
    public function getBuilder()
    {
        return new BasePluginConfigurationBuilder();
    }
}