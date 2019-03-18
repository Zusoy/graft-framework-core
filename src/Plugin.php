<?php

namespace Graft;

use Graft\Application;
use Graft\BasePluginConfigurationHandler;
use Graft\Definition\ConfigurationHandlerInterface;

/**
 * Graft Plugin Class
 * WordPress Plugin must extends this Class
 * 
 * @package  Graft
 * @category Framework
 * @author   Zusoy <gregoire.drapeau79@gmail.com>
 * @license  MIT
 * @since    0.0.1
 */
class Plugin extends Application
{
    /**
     * Plugin Instance
     *
     * @var self
     */
    private static $instance;


    /**
     * Plugin Constructor
     *
     * @param ConfigurationHandlerInterface|null $handler
     */
    final public function __construct(?ConfigurationHandlerInterface $handler = null)
    {
        if ($handler == null) {
            //get default Plugin Configuration Handler
            $handler = new BasePluginConfigurationHandler();
        }

        parent::__construct($handler);

        $this->setupPlugin();
        $this->registerPluginHooks();

        //get Plugin Instance
        self::$instance = $this;
    }


    /**
     * Get Current Plugin Instance
     *
     * @return self
     */
    public static function getCurrent()
    {
        if (self::$instance == null) {
            new Plugin();
        }

        return self::$instance;
    }


    /**
     * Setup Plugin Properties
     *
     * @return void
     */
    private function setupPlugin()
    {
        $pluginDatas = \get_file_data(
            $this->reflection->getFileName(),
            [
                'name'        => 'Plugin Name',
                'description' => 'Description',
                'author'      => 'Author',
                'version'     => 'Version'
            ]
        );

        foreach ($pluginDatas as $property => $value)
        {
            $this->{$property} = $value;
        }
    }


    /**
     * Hook Plugin Events
     * Register Plugin Statics Methods
     *
     * @return void
     */
    private function registerPluginHooks()
    {
        //register Activation Hook
        if (\method_exists(\get_called_class(), 'Activate')) {
            register_activation_hook(
                $this->reflection->getFileName(), 
                [\get_called_class(), 'Activate']
            );
        }

        //register Deactivation Hook
        if (\method_exists(\get_called_class(), 'Deactivate')) {
            register_deactivation_hook(
                $this->reflection->getFileName(),
                [\get_called_class(), 'Deactivate']
            );
        }

        //register Uninstall Hook
        if (\method_exists(\get_called_class(), 'Uninstall')) {
            register_uninstall_hook(
                $this->reflection->getFileName(),
                [\get_called_class(), 'Uninstall']
            );
        }
    }
}