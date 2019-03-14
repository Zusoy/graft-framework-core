<?php

namespace Graft;

use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

/**
 * Default Plugin Configuration Builder
 * 
 * @final
 * 
 * @package  Graft
 * @category BaseComponent
 * @author   Zusoy <gregoire.drapeau79@gmail.com>
 * @license  MIT
 * @since    0.0.1
 */
final class BasePluginConfigurationBuilder implements ConfigurationInterface
{
    /**
     * Get Plugin Configuration Tree Builder
     *
     * @return TreeBuilder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder("plugin");

        return $treeBuilder;
    }
}