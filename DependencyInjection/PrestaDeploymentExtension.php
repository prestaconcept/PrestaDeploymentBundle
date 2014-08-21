<?php
/**
 * This file is part of the PrestaDeploymentBundle.
 *
 * (c) PrestaConcept <http://www.prestaconcept.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Presta\DeploymentBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 */
class PrestaDeploymentExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');

        $configurationManager = $container->getDefinition('presta_deployment.manager.configuration');
        if ($config['persistence']['orm']['enabled']) {
            $configurationManager->addMethodCall('setOrmEnabled', array(true));
        }
        if ($config['persistence']['phpcr']['enabled']) {
            $configurationManager->addMethodCall('setPhpcrEnabled', array(true));
        }
        if ($config['migration']['enabled']) {
            $configurationManager->addMethodCall('setMigrationEnabled', array(true));
        }
        if ($config['deploy']['rebuild']) {
            $configurationManager->addMethodCall('setDeployRebuildEnabled', array(true));
        }
    }
}
