<?php

namespace Graft\Framework;

use Graft\Framework\Definition\TemplateOverrideHandlerInterface;
use Graft\Framework\Plugin;

/**
 * Main Template Override Handler
 * 
 * @final
 * 
 * @package  Graft
 * @category BaseComponent
 * @author   Zusoy <gregoire.drapeau79@gmail.com>
 * @license  MIT
 * @since    0.0.5
 */
final class MainTemplateOverrideHandler implements TemplateOverrideHandlerInterface
{
    /**
     * Check if Template is Overrided
     *
     * @param string $template Template Name
     * 
     * @return boolean
     */
    public function isOverrided(string $template)
    {
        $templatePath = $this->getTemplateOverrideDirectory() . $template;

        return (\file_exists($templatePath));
    }


    /**
     * Get Template Overriding Directory
     *
     * @return string
     */
    public function getTemplateOverrideDirectory()
    {
        $pluginName = Plugin::getCurrent()->getName();
        $directory = \get_stylesheet_directory() . "/" . strtolower($pluginName) . "/";
        
        return $directory;
    }
}