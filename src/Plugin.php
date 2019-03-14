<?php

namespace Graft;

use Graft\Application;
use Graft\Definition\ConfigurationHandlerInterface;
use Graft\Definition\ApplicationInterface;

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
class Plugin extends Application implements ApplicationInterface
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
        $this->registerPluginHooks();
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