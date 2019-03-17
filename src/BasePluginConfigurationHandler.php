<?php

namespace Graft;

use Graft\Definition\ConfigurationHandlerInterface;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use \ComposerLocator;

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
     * Get Configuration Tree Builder
     *
     * @return TreeBuilder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder("plugin");
        
        //add nodes definitions
        $treeBuilder->getRootNode()
            ->children()
            ->arrayNode('plugin')
            ->children()
            ->booleanNode('dev')->defaultTrue()->end()
            ->scalarNode('capability')
            ->cannotBeEmpty()->defaultValue('manage_options')
            ->end()
            ->scalarNode('controller_dir')
            ->cannotBeEmpty()->defaultValue('src/Controller/')->end()
            ->scalarNode('entity_dir')
            ->cannotBeEmpty()->defaultValue('src/Entity/')->end()
            ->scalarNode('public_dir')
            ->cannotBeEmpty()->defaultValue('public/')->end()
            ->scalarNode('views_dir')
            ->cannotBeEmpty()->defaultValue('views/')->end()
            ->scalarNode('shortcode_dir')
            ->cannotBeEmpty()->defaultValue('src/Shortcode/')->end()
            ->scalarNode('namespace')
            ->cannotBeEmpty()->defaultValue('App')->end()
            ->arrayNode('components')
            ->prototype('scalar')->cannotBeEmpty()->end()->end()
            ->end()
            ->end();

        return $treeBuilder;
    }
}