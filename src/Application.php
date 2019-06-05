<?php

namespace Graft\Framework;

use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Yaml\Yaml;
use Graft\Framework\Component\Factory;
use Graft\Framework\Definition\ConfigurationHandlerInterface;
use Graft\Framework\Exception\ConfigurationHandlerException;
use Graft\Framework\DefaultApplicationConfigHandler;
use Graft\Container\WPContainer;
use Graft\Framework\Plugin;
use DI\ContainerBuilder;
use \ReflectionClass;
use \ComposerLocator;

/**
 * Graft Main Application
 * 
 * @abstract
 * 
 * @package  GraftFramework
 * @category Framework
 * @author   Zusoy <gregoire.drapeau79@gmail.com>
 * @license  MIT
 * @since    0.0.1
 */
abstract class Application
{
    /**
     * Application Type Name
     * 
     * @var string
     */
    const PLUGIN = "Plugin";
    const BUNDLE = "Bundle";

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
     * Application Configuration Handlers
     *
     * @var ConfigurationHandlerInterface[]
     */
    protected $configHandlers = [];

    /**
     * Application Reflection Class
     *
     * @var ReflectionClass
     */
    protected $reflection;

    /**
     * Application Container
     *
     * @var WPContainer
     */
    protected $container;


    /**
     * Application Constructor
     *
     * @param ConfigurationHandlerInterface|null $handler Application Handler (optional)
     */
    public function __construct(?ConfigurationHandlerInterface $handler = null)
    {
        $this->reflection = new ReflectionClass(\get_class($this));

        //setup Application
        $this->setupApplication();
        $this->setupMainConfiguration();

        //add another configuration handler
        if ($handler !== null) {
            $this->addConfigHandler($handler);
        }

        //process the Application Configuration
        $this->processConfiguration();

        //build Application
        $this->buildContainer();
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
     * @param string|null $key Configuration Name
     * 
     * @return mixed
     */
    public function getConfig(?string $key = null)
    {
        if ($key === null) {
            return $this->config;
        }

        return (isset($this->config[$key]))
            ? $this->config[$key]
            : null;
    }


    /**
     * Get Application Configuration Node Values
     *
     * @param string $parentKey Parent Key Name
     * @param string $childKey  Child Key Name
     * 
     * @return mixed
     */
    public function getConfigNode(string $parentKey, string $childKey)
    {
        if (!isset($this->config[$parentKey])) {
            return null;
        }

        return (isset($this->config[$parentKey][$childKey]))
            ? $this->config[$parentKey][$childKey]
            : null;
    }


    /**
     * Add Application Configuration Handler
     *
     * @param ConfigurationHandlerInterface $handler Configuration Handler Object
     * 
     * @throws ConfigurationHandlerException
     * 
     * @return self
     */
    public function addConfigHandler(ConfigurationHandlerInterface $handler)
    {
        if ($this->getConfigHandler($handler->getConfigName()) !== null) {
            throw new ConfigurationHandlerException(
                "Configuration Handler with name '"
                .$handler->getConfigName()."' already exist in '"
                .$this->getName()."' Application."
            );
        }

        $this->configHandlers[] = $handler;
        
        return $this;
    }


    /**
     * Get Application Configuration Handler by Name
     *
     * @param string $handlerName Config Handler name
     * 
     * @return ConfigurationHandlerInterface|null
     */
    public function getConfigHandler(string $handlerName)
    {
        foreach ($this->configHandlers as $configHandler)
        {
            if ($configHandler->getConfigName() === $handlerName)
            {
                return $configHandler;
            }
        }

        return null;
    }


    /**
     * Get All Application Configuration Handler
     *
     * @return ConfigurationHandlerInterface[]
     */
    public function getConfigHandlers()
    {
        return $this->configHandlers;
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
     * Get Application Container
     *
     * @return WPContainer
     */
    public function getContainer()
    {
        return $this->container;
    }


    /**
     * Get Application Directory
     *
     * @return string
     */
    public function getDirectory()
    {
        return \dirname(
            $this->reflection->getFileName()
        );
    }


    /**
     * Check if Application is an Plugin
     *
     * @return boolean
     */
    public function isPlugin()
    {
        $parent = $this->reflection->getParentClass();

        return ($parent->getName() == Plugin::class);
    }


    /**
     * Setup Application Properties
     *
     * @return void
     */
    private function setupApplication()
    {
        $appType = ($this->isPlugin())
            ? Application::PLUGIN
            : Application::BUNDLE;
        
        $appDatas = \get_file_data(
            $this->reflection->getFileName(),
            [
                'name'        => $appType . " Name",
                'description' => "Description",
                'author'      => "Author",
                'version'     => "Version" 
            ]
        );

        foreach ($appDatas as $propertyName => $propertyValue) {
            $this->{$propertyName} = $propertyValue;
        }
    }


    /**
     * Setup Main Application Configuration
     *
     * @return void
     */
    private function setupMainConfiguration()
    {
        $mainConfigHandler = new DefaultApplicationConfigHandler();
        $configDir = ($this->isPlugin())
            ? ComposerLocator::getRootPath() . "/config/"
            : ComposerLocator::getRootPath() . "/config/bundles/";
        $mainConfigHandler->setDirectory($configDir);
        
        //add default application configuration handler
        $this->addConfigHandler($mainConfigHandler);
    }


    /**
     * Process the Application Configuration
     *
     * @return void
     */
    private function processConfiguration()
    {
        $processor = new Processor();

        foreach ($this->configHandlers as $configHandler)
        {
            $file = $configHandler->getConfigFile();
            
            $config = Yaml::parse(
                \file_get_contents($file)
            );
            $config = [$config];

            $this->config = \array_merge(
                $this->config,
                $processor->processConfiguration(
                    $configHandler,
                    $config
                )
            );
        }
    }


    /**
     * Build Application Container
     *
     * @return void
     */
    private function buildContainer()
    {
        $appNamespace = $this->getConfigNode("application", "namespace");
        $factory = new Factory($appNamespace);

        $containerBuilder = new ContainerBuilder(WPContainer::class);
        $containerBuilder->useAnnotations(false); //disable PHP-DI Annotations
        $containerBuilder->useAutowiring(true);
        $container = $containerBuilder->build();

        //add container parameters from container config file
        $parameters = Plugin::getCurrent()->getConfigNode('container', 'parameters');
        if (count($parameters) > 0) {
            $container->addParameters($parameters);
        }

        $this->container = $factory->build($container);
    }
}