<?php

namespace Graft\Framework;

use Graft\Framework\Definition\ConfigurationHandlerInterface;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

/**
 * Default Application Configuration Handler
 * 
 * @final
 * 
 * @package  GraftFramework
 * @category DefaultComponent
 * @author   Zusoy <gregoire.drapeau79@gmail.com>
 * @license  MIT
 * @since    0.0.2
 */
final class DefaultApplicationConfigHandler implements ConfigurationHandlerInterface
{
    /**
     * Configuration File Directory
     *
     * @var string
     */
    private $directory;


    /**
     * Get Configuration Name
     *
     * @return string
     */
    public function getConfigName()
    {
        return "application";
    }


    /**
     * Get Configuration File
     *
     * @return string
     */
    public function getConfigFile()
    {
        return $this->directory . "application.yaml";
    }


    /**
     * Get Configuration Tree Builder
     *
     * @return TreeBuilder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder("application");
        
        //add nodes definitions
        $treeBuilder->getRootNode()
            ->children()
                ->arrayNode('application')
                    ->children()
                    ->booleanNode('dev')
                        ->defaultTrue()
                    ->end()
                    ->scalarNode('capability')
                        ->cannotBeEmpty()->defaultValue('manage_options')
                    ->end()
                    ->scalarNode('asset_dir')
                        ->cannotBeEmpty()->defaultValue('assets/')
                    ->end()
                    ->scalarNode('template_dir')
                        ->cannotBeEmpty()->defaultValue('templates/')
                    ->end()
                    ->scalarNode('namespace')
                        ->cannotBeEmpty()->defaultValue('App')
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }


    /**
     * Set Main ConfigurationHandler Directory
     *
     * @param string $directory The Config File's Directory
     * 
     * @return self
     */
    public function setDirectory(string $directory)
    {
        $this->directory = $directory;

        return $this;
    }


    /**
     * Get Main ConfigurationHandler File Directory
     *
     * @return string
     */
    public function getDirectory()
    {
        return $this->directory;
    }
}