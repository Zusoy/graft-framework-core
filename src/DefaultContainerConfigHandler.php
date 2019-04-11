<?php

namespace Graft\Framework;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Graft\Framework\Definition\ConfigurationHandlerInterface;

/**
 * Default Container Configuration Handler
 * 
 * @final
 * 
 * @package  Graft
 * @category DefaultComponent
 * @author   Zusoy <gregoire.drapeau79@gmail.com>
 * @license  MIT
 * @since    0.0.7
 */
final class DefaultContainerConfigHandler implements ConfigurationHandlerInterface
{
    /**
     * Container Configuration File Directory
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
        return 'container';   
    }


    /**
     * Get Configuration File
     *
     * @return string
     */
    public function getConfigFile()
    {
        return $this->directory . "container.yaml";
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
                ->end()
            ->end();

        return $treeBuilder;
    }


    /**
     * Set Container Configuration Directory
     *
     * @param string $directory Container Configuration File Directory
     * 
     * @return self
     */
    public function setDirectory(string $directory)
    {
        $this->directory = $directory;

        return $this;
    }


    /**
     * Get Container Configuration Directory
     *
     * @return string
     */
    public function getDirectory()
    {
        return $this->directory;
    }
}