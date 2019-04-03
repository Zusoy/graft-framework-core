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
        $directory = $this->getTemplateOverrideDirectory();

        if (!\is_dir($directory)) {
            return false;
        }

        $templatePath = $directory . $template;

        return (\is_file($templatePath));
    }


    /**
     * Get Template Overriding Directory
     *
     * @return string
     */
    public function getTemplateOverrideDirectory()
    {
        $pluginName = \trim(Plugin::getCurrent()->getName());
        $pluginName = \str_replace(' ', '', $pluginName);
        $directory = \get_stylesheet_directory() . "/" . strtolower($pluginName) . "/";
        
        return $directory;
    }
}