<?php
require __DIR__ . "/../../../../../wordpress/wp-load.php";

use PHPUnit\Framework\TestCase;
use Graft\Test\Fake\FakeConfigHandler;
use Graft\Framework\Plugin;

/**
 * Test Plugin and Application Classes Behaviour
 */
class PluginTest extends TestCase
{
    /**
     * Core Plugin Class
     *
     * @var GraftCorePlugin
     */
    private $corePlugin;


    /**
     * PluginTest Constructor
     */
    public function __construct()
    {
        require_once dirname(__DIR__) . "/index.php";
        
        //add custom config handler to test configurations
        $fakeConfigHandler = new FakeConfigHandler();
        $this->corePlugin = new GraftCorePlugin($fakeConfigHandler);
    }


    /**
     * Test Plugin Singleton
     *
     * @return void
     */
    public function testPluginSingleton()
    {
        $currentPlugin = Plugin::getCurrent();

        $this->assertTrue($currentPlugin instanceof GraftCorePlugin);
    }


    /**
     * Test Plugin Configuration
     *
     * @return void
     */
    public function testPluginConfiguration()
    {
        
    }
}