<?php

namespace Graft;

use \ComposerLocator;
use Graft\BasePluginConfigurationBuilder;
use Graft\Definition\ConfigurationHandlerInterface;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Config\Definition\Processor;
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
     * Plugin Configuration Values
     *
     * @var mixed
     */
    private $values;

    /**
     * Plugin Configuration Builder
     *
     * @var ConfigurationInterface
     */
    private $builder;


    /**
     * BasePluginConfigurationHandler Constructor
     */
    public function __construct()
    {
        $this->builder = $this->getBuilder();
        $this->processConfiguration();
    }


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


    /**
     * Get Plugin Configuration Values
     *
     * @return mixed
     */
    public function getValues()
    {
        return $this->values;
    }


    /**
     * Process Plugin Configuration
     *
     * @return void
     */
    public function processConfiguration()
    {
        $processor = new Processor();
        $file = $this->getFile();

        $config = Yaml::parse(
            \file_get_contents($file)
        );

        $this->values = $processor->processConfiguration(
            $this->builder,
            [$config]
        );
    }
}