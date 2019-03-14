<?php

namespace Graft;

use Graft\Definition\ConfigurationHandlerInterface;
use Graft\Definition\ApplicationInterface;
use \ReflectionClass;
use \Exception;

/**
 * Graft Main Application
 * 
 * @abstract
 * 
 * @package  Graft
 * @category Framework
 * @author   Zusoy <gregoire.drapeau79@gmail.com>
 * @license  MIT
 * @since    0.0.1
 */
abstract class Application implements ApplicationInterface
{
    /**
     * Application Name
     *
     * @var string
     */
    protected $name;

    /**
     * Application Description
     *
     * @var string
     */
    protected $description;

    /**
     * Application Configuration Handler
     *
     * @var ConfigurationHandlerInterface
     */
    protected $configHandler = null;

    /**
     * Application Reflection Class
     *
     * @var ReflectionClass
     */
    protected $reflection;


    /**
     * Application Constructor
     *
     * @param ConfigurationHandlerInterface $handler Configuration Handler
     */
    public function __construct(ConfigurationHandlerInterface $handler)
    {
        //setup Application with Implementation
        $this->setup();
        $this->configHandler = $handler;
        $this->reflection = new ReflectionClass(\get_class($this));

        $this->checkApplication();
    }


    /**
     * Check Application Components
     * 
     * @throws Exception
     *
     * @return void
     */
    private function checkApplication()
    {
        if ($this->name == null || empty($this->name)) {
            throw new Exception("Graft : Trying to create Application without valid Name.");
        }

        if ($this->description == null || empty($this->description)) {
            throw new Exception("Invalid Description for ".$this->name." Application");
        }

        if ($this->configHandler == null) {
            throw new Exception("No Configuration Handler for ".$this->name." Application");
        }
    }
}