<?php

namespace Graft;

use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Yaml\Yaml;
use Graft\Definition\ConfigurationHandlerInterface;
use \ReflectionClass;

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
abstract class Application
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
     * Application Author
     *
     * @var string
     */
    protected $author;

    /**
     * Application Version
     *
     * @var string
     */
    protected $version;

    /**
     * Application Configuration Values
     *
     * @var array
     */
    protected $config = [];

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
        $this->configHandler = $handler;
        $this->reflection = new ReflectionClass(\get_class($this));

        $this->processConfiguration();
    }


    /**
     * Get Application Name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }


    /**
     * Get Application Description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }


    /**
     * Get Application Author
     *
     * @return string
     */
    public function getAuthor()
    {
        return $this->author;
    }


    /**
     * Get Application Version
     *
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }


    /**
     * Get Application Configuration Values
     *
     * @return array
     */
    public function getConfig()
    {
        return $this->config;
    }


    /**
     * Get Application Configuration Handler
     *
     * @return ConfigurationHandlerInterface
     */
    public function getConfigHandler()
    {
        return $this->configHandler;
    }


    /**
     * Get Application Reflection Class
     *
     * @return ReflectionClass
     */
    public function getReflection()
    {
        return $this->reflection;
    }


    /**
     * Process the Application Configuration
     *
     * @return void
     */
    private function processConfiguration()
    {
        $processor = new Processor();
        $file = $this->configHandler->getConfigFile();

        $config = Yaml::parse(
            \file_get_contents($file)
        );
        $config = [$config];

        //process configuration
        $this->config = $processor->processConfiguration(
            $this->configHandler,
            $config
        );
    }
}