<?php

namespace Graft;

use \Exception;
use \ReflectionClass;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Yaml\Yaml;
use Graft\Definition\ConfigurationHandlerInterface;

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
     * Process the Application Configuration
     *
     * @return void
     */
    private function processConfiguration()
    {
        $processor = new Processor();
        $file = $this->configHandler->getFile();
        $builder = $this->configHandler->getBuilder();

        $config = Yaml::parse(
            \file_get_contents($file)
        );
        $config = [$config];

        $this->config = $processor->processConfiguration(
            $builder,
            $config
        );
    }
}