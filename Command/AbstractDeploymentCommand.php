<?php
/**
 * This file is part of the PrestaDeploymentBundle.
 *
 * (c) PrestaConcept <http://www.prestaconcept.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Presta\DeploymentBundle\Command;

use Presta\DeploymentBundle\Manager\ConfigurationManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 */
abstract class AbstractDeploymentCommand extends ContainerAwareCommand
{
    /**
     * @var InputInterface
     */
    protected $input;

    /**
     * @var OutputInterface
     */
    protected $output;

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function init(InputInterface $input, OutputInterface $output)
    {
        $this->input  = $input;
        $this->output = $output;
    }

    /**
     * @return ConfigurationManager
     */
    protected function getConfigurationManager()
    {
        return $this->getContainer()->get('presta_deployment.manager.configuration');
    }

    /**
     * @param string $message
     */
    protected function log($message)
    {
        $this->output->writeln('[presta-deployment] ' . $message);
    }
}
