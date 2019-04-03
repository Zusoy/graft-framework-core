<?php

namespace Graft\Framework\Definition;

/**
 * Renderer Interface
 * 
 * @package  Graft/Definition
 * @category Definition
 * @author   Zusoy <gregoire.drapeau79@gmail.com>
 * @license  MIT
 * @since    0.0.5
 */
interface RendererInterface
{
    /**
     * Render an Template
     *
     * @param string $template    Template Name
     * @param array  $params      Template Parameters (optional)
     * @param bool   $overridable Template Overridable (optional)
     * 
     * @return string
     */
    public function render(string $template, array $params = [], bool $overridable = true);

    /**
     * Display an Template
     *
     * @param string $template    Template Name
     * @param array  $params      Template Parameters (optional)
     * @param bool   $overridable Template Overridable (optional)
     * 
     * @return void
     */
    public function display(string $template, array $params = [], bool $overridable = true);
}