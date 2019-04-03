<?php

namespace Graft\Framework\Twig;

use Graft\Framework\Plugin;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * WordPress Twig Extension
 * 
 * @final
 * 
 * @package  Graft/Twig
 * @category TwigExtension
 * @author   Zusoy <gregoire.drapeau79@gmail.com>
 * @license  MIT
 * @since    0.0.5
 */
final class WordpressTwigExtension extends AbstractExtension
{
    /**
     * Get Functions Extensions
     *
     * @return TwigFunction[]|array
     */
    public function getFunctions()
    {
        return [
            new TwigFunction("do_action", [$this, "doAction"]),
            new TwigFunction("apply_filters", [$this, "applyFilters"]),
            new TwigFunction("do_shortcode", [$this, "doShortcode"])
        ];
    }


    /**
     * Do WordPress Action
     * 
     * @param string $tag    Tag Name
     * @param mixed  ...$arg Arguments
     *
     * @return void
     */
    public function doAction(string $tag, ...$arg)
    {
        \do_action($tag, ...$arg);
    }


    /**
     * Apply WordPress Filters
     *
     * @param string $tag     Tag Name
     * @param mixed   ...$arg Arguments
     * 
     * @return mixed
     */
    public function applyFilters(string $tag, ...$arg)
    {
        return \apply_filters($tag, ...$arg);
    }


    /**
     * Invok WordPress Shortcode
     *
     * @param string $content    Content to search for shortcodes.
     * @param bool   $ignoreHtml When true, shortcodes inside HTML elements will be skipped. (optional)
     * 
     * @return mixed
     */
    public function doShortcode(string $content, bool $ignoreHtml = false)
    {
        return \do_shortcode($content, $ignoreHtml);
    }
}