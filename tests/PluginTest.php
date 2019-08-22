<?php
require __DIR__ . "/../../../../../wordpress/wp-load.php";

use PHPUnit\Framework\TestCase;
use Graft\Test\Fake\FakeConfigHandler;
use Graft\Framework\Plugin;

/**
 * Test Plugin and Application Classes Behaviour
 * 
 * @category Test
 */
class PluginTest extends TestCase
{
    /**
     * Core Plugin
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
     * Test Plugin Informations 
     * Plugin's properties (name, description...) have to be the same as the plugin's file (comments)
     *
     * @return void
     */
    public function testPluginInformations()
    {
        $pluginFile = $this->corePlugin->getReflection()->getFileName();
        $fileMetaDatas = \get_file_data(
            $pluginFile, [
                'getName'        => "Plugin Name",
                'getDescription' => "Description",
                'getAuthor'      => "Author",
                'getVersion'     => "Version"
            ]
        );
        
        foreach ($fileMetaDatas as $getter => $value) 
        {
            $this->assertEquals($value, $this->corePlugin->{$getter}());
        }
    }


    /**
     * Test Custom Plugin Configuration
     *
     * @return void
     */
    public function testPluginConfiguration()
    {
        $config = $this->corePlugin->getConfig();

        $this->assertTrue(\array_key_exists("fake", $config));
    }
}