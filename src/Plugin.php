<?php

namespace Graft\Framework;

use Graft\Framework\Application;
use Graft\Framework\Definition\ConfigurationHandlerInterface;

/**
 * Graft Plugin Class
 * WordPress Plugin must extends this Class
 * 
 * @package  GraftFramework
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
     * @static
     *
     * @var self
     */
    private static $instance;


    /**
     * Plugin Constructor
     * 
     * @final
     *
     * @param ConfigurationHandlerInterface|null $handler Application Handler (optional)
     */
    final public function __construct(?ConfigurationHandlerInterface $handler = null)
    {
        //set Plugin Instance
        self::$instance = $this;

        parent::__construct($handler);

        $this->registerPluginHooks();
    }


    /**
     * Get Current Plugin Instance
     * 
     * @static
     *
     * @return self
     */
    public static function getCurrent()
    {
        return self::$instance;
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