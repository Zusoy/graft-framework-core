<?php

namespace Graft\Framework\Definition;

/**
 * Template Override Handler
 * 
 * @package  Graft/Definition
 * @category Definition
 * @author   Zusoy <gregoire.drapeau79@gmail.com>
 * @license  MIT
 * @since    0.0.5
 */
interface TemplateOverrideHandlerInterface
{
    /**
     * Check if an Template was Overrided
     *
     * @param string $template Template Name to Check
     * 
     * @return boolean
     */
    public function isOverrided(string $template);

    /**
     * Get Template Overriding Directory
     *
     * @return string
     */
    public function getTemplateOverrideDirectory();
}