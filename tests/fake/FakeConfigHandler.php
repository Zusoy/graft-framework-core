<?php

namespace Graft\Test\Fake;

use Graft\Framework\Definition\ConfigurationHandlerInterface;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

/**
 * Fake Configuration Handler for Tests
 * 
 * @package  GraftFramework
 * @category Fake
 * @author   Zusoy <gregoire.drapeau79@gmail.com>
 * @license  MIT
 */
class FakeConfigHandler implements ConfigurationHandlerInterface
{
    /**
     * Get The Configuration Name
     *
     * @return string
     */
    public function getConfigName()
    {
        return "fake";
    }


    /**
     * Get Configuration File
     *
     * @param string $configDir Current Configuration Directory
     * 
     * @return string
     */
    public function getConfigFile(string $configDir)
    {
        return $configDir . "/fake.yaml";
    }


    /**
     * Get Configuration Builder
     *
     * @return TreeBuilder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder("fake");
        
        //add nodes definitions
        $treeBuilder->getRootNode()
            ->children()
                ->arrayNode('fake')
                    ->children()
                    ->booleanNode('dev')
                        ->defaultTrue()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}