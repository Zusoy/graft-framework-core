<?php

namespace Graft\Test\Fake\Controller;

use Graft\Framework\Annotation\Filter;
use Graft\Framework\Annotation\Action;
use Graft\Framework\Injectable\Renderer;

/**
 * HookController for Testing
 * 
 * @package  GraftFramework
 * @category Fake
 * @author   Zusoy <gregoire.drapeau79@gmail.com>
 * @license  MIT
 */
class HookController
{
    /**
     * Template Renderer
     *
     * @var Renderer
     */
    private $renderer;


    /**
     * HookController Constructor
     *
     * @param Renderer $renderer
     */
    public function __construct(Renderer $renderer)
    {
        $this->renderer = $renderer;
    }


    /**
     * The Title Hook
     * 
     * @Filter(name="the_title")
     * 
     * @param string $currentTitle Current Title
     *
     * @return void
     */
    public function hookTheTitle(string $currentTitle)
    {
        return $currentTitle . " Test";
    }


    /**
     * Init Hook
     * 
     * @Action(name="init", priority=50)
     *
     * @return void
     */
    public function hookInit()
    {
        //do nothing
    }
}