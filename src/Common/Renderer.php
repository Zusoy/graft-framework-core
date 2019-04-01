<?php

namespace Graft\Framework\Common;

use Graft\Framework\Definition\RendererInterface;
use Graft\Framework\Definition\TemplateOverrideHandlerInterface;
use Graft\Framework\MainTemplateOverrideHandler;
use Graft\Framework\Twig\WordpressTwigExtension;
use Graft\Framework\Plugin;
use Twig\Loader\FilesystemLoader;
use Twig\Environment;

/**
 * Template Rendering
 * 
 * @package  Graft/Common
 * @category Common
 * @author   Zusoy <gregoire.drapeau79@gmail.com>
 * @license  MIT
 * @since    0.0.5
 */
class Renderer implements RendererInterface
{
    /**
     * Main Twig Environment
     *
     * @var Environment
     */
    protected $twig;

    /**
     * Main Twig Loader
     *
     * @var FilesystemLoader
     */
    private $loader;

    /**
     * Theme Twig Environment
     *
     * @var Environment
     */
    protected $twigTheme;

    /**
     * Theme Twig Loader
     *
     * @var FilesystemLoader
     */
    private $themeLoader;

    /**
     * Template Override Handler
     *
     * @var TemplateOverrideHandlerInterface
     */
    protected $overrideHandler;


    /**
     * Renderer Constructor
     *
     * @param TemplateOverrideHandlerInterface|null $overrideHandler Template Override Handler (optional)
     */
    public function __construct(?TemplateOverrideHandlerInterface $overrideHandler = null)
    {
        if ($overrideHandler === null) {
            $overrideHandler = new MainTemplateOverrideHandler();
        }

        $this->setTemplateOverrideHandler($overrideHandler);
        $this->initTwigEnvironments();
    }


    /**
     * Render Template
     *
     * @param string  $template    Template Name
     * @param array   $params      Template Parameters (optional)
     * @param boolean $overridable Template Overridable (optional)
     * 
     * @return string
     */
    public function render(string $template, array $params = [], bool $overridable = true)
    {
        return ($this->overrideHandler->isOverrided($template) && $overridable)
            ? $this->twigTheme->render($template, $params)
            : $this->twig->render($template, $params);
    }


    /**
     * Display Template
     *
     * @param string  $template    Template Name
     * @param array   $params      Template Parameters (optional)
     * @param boolean $overridable Template Overridable (optional)
     * 
     * @return void
     */
    public function display(string $template, array $params = [], bool $overridable = true)
    {
        echo ($this->overrideHandler->isOverrided($template) && $overridable)
            ? $this->twigTheme->render($template, $params)
            : $this->twig->render($template, $params);
    }


    /**
     * Set Renderer Template Override Handler
     *
     * @param TemplateOverrideHandlerInterface $handler Template Override Handler
     * 
     * @return self
     */
    public function setTemplateOverrideHandler(TemplateOverrideHandlerInterface $handler)
    {
        $this->overrideHandler = $handler;

        return $this;
    }


    /**
     * Get Renderer Template Override Handler
     *
     * @return TemplateOverrideHandlerInterface
     */
    public function getTemplateOverrideHandler()
    {
        return $this->overrideHandler;
    }


    /**
     * Init Renderer Twig Environments
     *
     * @return void
     */
    private function initTwigEnvironments()
    {
        $appViews = Plugin::getCurrent()->getConfigNode(
            "application", 
            "template_dir"
        );
        $appViews = Plugin::getCurrent()->getDirectory() . "/" . $appViews;

        if (\is_dir($appViews))
        {
            //set main environments
            $this->loader = new FilesystemLoader($appViews);
            $this->twig = new Environment($this->loader);
        }

        if (\is_dir($this->overrideHandler->getTemplateOverrideDirectory()))
        {
            //set override environments
            $this->themeLoader = new FilesystemLoader(
                $this->overrideHandler->getTemplateOverrideDirectory()
            );
            $this->twigTheme = new Environment($this->themeLoader);
        }

        //add Environments Extensions
        $engines = [$this->twig, $this->twigTheme];
        foreach ($engines as $engine)
        {
            if ($engine !== null)
            {
                $engine->addExtension(new WordpressTwigExtension());
            }
        }
    }
}