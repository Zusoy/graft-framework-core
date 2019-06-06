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
     * @param string $configDir Current Application Configuration Directory
     *
     * @return string
     */
    public function getConfigFile(string $configDir)
    {
        return $configDir . "application.yaml";
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
}