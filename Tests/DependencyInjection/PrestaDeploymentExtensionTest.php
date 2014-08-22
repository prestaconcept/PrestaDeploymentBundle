<?php
/**
 * This file is part of the PrestaDeploymentBundle.
 *
 * (c) PrestaConcept <http://www.prestaconcept.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Presta\DeploymentBundle\Tests\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

use Presta\DeploymentBundle\DependencyInjection\PrestaDeploymentExtension;

/**
 * ./vendor/bin/phpunit Tests/DependencyInjection/PrestaDeploymentExtensionTest.php
 *
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 */
class PrestaDeploymentExtensionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var PrestaDeploymentExtension
     */
    protected $extension;

    /**
     * @var ContainerBuilder
     */
    protected $container;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->extension = new PrestaDeploymentExtension();

        $this->container = new ContainerBuilder();
        $this->container->registerExtension($this->extension);
    }

    /**
     * @test PrestaDeploymentExtension::load()
     */
    public function testLoadWithoutConfiguration()
    {
        $this->container->loadFromExtension($this->extension->getAlias());
        $this->container->compile();

        $this->assertTrue($this->container->has('presta_deployment.manager.configuration'));

        $configurationManager = $this->container->get('presta_deployment.manager.configuration');
        $this->assertFalse($configurationManager->isOrmEnabled());
        $this->assertFalse($configurationManager->isPhpcrEnabled());
        $this->assertFalse($configurationManager->isMigrationEnabled());
        $this->assertFalse($configurationManager->isDeployRebuildEnabled());
    }

    /**
     * @test PrestaDeploymentExtension::load()
     */
    public function testLoadWithYamlConfiguration()
    {
        $loader = new YamlFileLoader($this->container, new FileLocator(__DIR__.'/Fixtures/'));
        $loader->load('config.yml');
        $this->container->compile();

        $this->assertTrue($this->container->has('presta_deployment.manager.configuration'));

        $configurationManager = $this->container->get('presta_deployment.manager.configuration');
        $this->assertTrue($configurationManager->isOrmEnabled());
        $this->assertTrue($configurationManager->isPhpcrEnabled());
        $this->assertTrue($configurationManager->isMigrationEnabled());
        $this->assertTrue($configurationManager->isDeployRebuildEnabled());
    }
}
