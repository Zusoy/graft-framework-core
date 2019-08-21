<?php

namespace Graft\Test;

use Graft\Framework\Annotation\AdminMenu;
use Graft\Framework\Injectable\Renderer;

class AdminController
{
    /**
     * Template Renderer
     *
     * @var Renderer
     */
    private $renderer;


    /**
     * AdminController Constructor
     *
     * @param Renderer $renderer
     */
    public function __construct(Renderer $renderer)
    {
        $this->renderer = $renderer;
    }


    /**
     * Generate admin menu
     * 
     * @AdminMenu(title="Core")
     *
     * @return void
     */
    public function adminMenu()
    {

    }
}