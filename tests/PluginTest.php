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
     * Configuration Values for Tests
     *
     * @var array
     */
    private $testConfigValues = [
        "application" => [
            "dev"       => true,
            "test"      => "Hello World !",
            "namespace" => "App"
        ]
    ];


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


    /**
     * Test Get Plugin Configuration without Key
     *
     * @return void
     */
    public function testPluginGetConfigWithoutKey()
    {
        $reflection = new ReflectionClass($this->corePlugin);
        $configProperty = $reflection->getProperty("config");

        $configProperty->setAccessible(true);
        $configProperty->setValue($this->corePlugin, $this->testConfigValues);

        $this->assertEquals($this->testConfigValues, $this->corePlugin->getConfig());
    }


    /**
     * Test Get Plugin Configuration with Key
     *
     * @return void
     */
    public function testPluginGetConfigWithKey()
    {
        $reflection = new ReflectionClass($this->corePlugin);
        $configProperty = $reflection->getProperty("config");

        $configProperty->setAccessible(true);
        $configProperty->setValue($this->corePlugin, $this->testConfigValues);

        $this->assertEquals(
            $this->testConfigValues['application'], 
            $this->corePlugin->getConfig('application')
        );
    }


    /**
     * Test Get Plugin Configuration Node
     *
     * @return void
     */
    public function testPluginGetConfigNode()
    {
        $reflection = new ReflectionClass($this->corePlugin);
        $configProperty = $reflection->getProperty("config");

        $configProperty->setAccessible(true);
        $configProperty->setValue($this->corePlugin, $this->testConfigValues);

        $this->assertEquals(
            $this->testConfigValues['application']['test'],
            $this->corePlugin->getConfigNode('application', 'test')
        );
    }
}