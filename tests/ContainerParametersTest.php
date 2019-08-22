<?php
require __DIR__ . "/../../../../../wordpress/wp-load.php";

use PHPUnit\Framework\TestCase;
use Symfony\Component\Yaml\Yaml;
use Graft\Container\Parameter;
use Graft\Container\Resolver\ParameterValueResolver;
use Graft\Container\Exception\ParameterException;
use Graft\Container\Exception\ParameterNotFoundException;
use Graft\Container\Exception\ParameterAlreadyExistException;

/**
 * Test Container Parameters
 * 
 * @category Test
 */
class ContainerParametersTest extends TestCase
{
    /**
     * Core Plugin
     *
     * @var GraftCorePlugin
     */
    private $corePlugin;

    /**
     * Tests Parameters
     *
     * @var array
     */
    private $testParameters = [
        "parameters" => [
            "firstParam"  => "Hello World !",
            "secondParam" => true
        ]
    ];


    /**
     * ContainerTest Constructor
     */
    public function __construct()
    {
        require_once dirname(__DIR__) . "/index.php";
        $this->writeParametersForTests();

        $this->corePlugin = new GraftCorePlugin();
    }


    /**
     * Test Container Parameters Values from Container Configuration File
     *
     * @return void
     */
    public function testParametersValues()
    {
        foreach ($this->testParameters['parameters'] as $name => $value)
        {
            try {
                $parameter = $this->corePlugin->getContainer()->getParameter($name);
                $this->assertEquals($value, $parameter->getValue());
            } catch(ParameterException $exception) {
                print("Parameter {$name} was not found in the Container");
            }
        }
    }


    /**
     * Test Parameter Not Found Exception
     *
     * @return void
     */
    public function testParameterNotFoundException()
    {
        $this->expectException(ParameterNotFoundException::class);
        $this->corePlugin->getContainer()->getParameter("notExistParam");
    }


    /**
     * Test Parameter Already Exist Exception
     *
     * @return void
     */
    public function testParameterAlreadyExistException()
    {
        $parameter = new Parameter("firstParam", "test");
        $this->expectException(ParameterAlreadyExistException::class);

        $this->corePlugin->getContainer()->addParameter($parameter);
    }


    /**
     * Test Parameter Value Resolver
     *
     * @return void
     */
    public function testParameterResolver()
    {
        $resolver = new ParameterValueResolver();
        $parameter = $resolver->resolveParameter(
            "%firstParam%", 
            $this->corePlugin->getContainer()
        );

        $this->assertEquals(
            $this->testParameters['parameters']['firstParam'],
            $parameter->getValue()
        );
    }


    /**
     * Write some Parameters in Container Config File for Tests
     *
     * @return void
     */
    private function writeParametersForTests()
    {
        $yaml = Yaml::dump([
            "container" => $this->testParameters
        ]);
        $containerConfigFile = dirname(__DIR__) . "/config/container.yaml";
        file_put_contents($containerConfigFile, $yaml);
    }
}