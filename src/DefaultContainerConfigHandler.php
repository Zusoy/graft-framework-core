<?php

namespace Graft\Framework;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Graft\Framework\Definition\ConfigurationHandlerInterface;

/**
 * Default Container Configuration Handler
 * 
 * @final
 * 
 * @package  GraftFramework
 * @category DefaultComponent
 * @author   Zusoy <gregoire.drapeau79@gmail.com>
 * @license  MIT
 * @since    0.0.7
 */
final class DefaultContainerConfigHandler implements ConfigurationHandlerInterface
{
    /**
     * Get Configuration Name
     *
     * @return string
     */
    public function getConfigName()
    {
        return 'container';   
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
        return $configDir . "container.yaml";
    }


    /**
     * Get Configuration Tree Builder
     *
     * @return TreeBuilder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder("container");
        
        //add nodes definitions
        $treeBuilder->getRootNode()
            ->children()
                ->arrayNode('container')
                    ->children()
                    ->arrayNode('parameters')
                        ->prototype('scalar')
                            ->cannotBeEmpty()
                        ->end()
                    ->end()
                    ->booleanNode('autowiring')
                        ->defaultTrue()
                    ->end()
                    ->booleanNode('annotation')
                        ->defaultFalse()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}