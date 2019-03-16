<?php

namespace Graft;

use Graft\Application;
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
     * Plugin Constructor
     * 
     * @final
     *
     * @param ConfigurationHandlerInterface $handler Configuration Handler
     */
    final public function __construct(ConfigurationHandlerInterface $handler)
    {
        parent::__construct($handler);

        $this->setupPlugin();
        $this->registerPluginHooks();
    }


    /**
     * Setup Plugin Properties
     *
     * @return void
     */
    private function setupPlugin()
    {
        $data = get_plugin_data($this->reflection->getFileName());

        $this->name = $data['Name'];
        $this->description = $data['Description'];
        $this->author = $data['Author'];
        $this->version = $data['Version'];
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