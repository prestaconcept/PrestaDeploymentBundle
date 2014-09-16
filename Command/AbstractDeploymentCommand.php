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

use Presta\DeploymentBundle\Exception\EnvironmentException;
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
        $this->output->writeln(
            $this->getHelper('formatter')->formatSection('presta-deployment', $message)
        );
    }

    /**
     * Check if the task runs in the correct environment
     *
     * @param  string $environment
     *
     * @throws EnvironmentException
     */
    protected function checkEnvironment($environment)
    {
        if ($this->input->getOption('env') != $environment) {
            throw new EnvironmentException('this task is only available for environment : ' . $environment);
        }
    }

    /**
     * Clear application cache
     */
    protected function clearCache()
    {
        $application  = $this->getApplication();
        $commandInput = new ArrayInput(array('command' => 'cache:clear'));
        $application->doRun($commandInput, $this->output);
    }
}
