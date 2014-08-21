<?php
/**
 * This file is part of the PrestaDeploymentBundle.
 *
 * (c) PrestaConcept <http://www.prestaconcept.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Presta\DeploymentBundle\Command\Test;

use Presta\DeploymentBundle\Command\AbstractDeploymentCommand;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 */
class CleanCommand extends AbstractDeploymentCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('presta:deployment:test-clean')
            ->setDescription('Clean test data');
    }

    /**
     * {@inheritdoc}
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $this->init($input, $output);
        $this->checkEnvironment('test');

        $this->log('Clean test data ..');

        if ($this->getConfigurationManager()->isOrmEnabled()) {
            $this->cleanOrm();
        }
        if ($this->getConfigurationManager()->isPhpcrEnabled()) {
            $this->cleanPhpcr();
        }

        $this->log('Clean test data done..');
    }

    /**
     * Drop ORM database
     */
    protected function cleanOrm()
    {
        $application = $this->getApplication();
        $commandInput = new ArrayInput(array(
            'command'   => 'doctrine:database:drop',
            '--force'   => '--force'
        ));
        $application->doRun($commandInput, $this->output);

        $connection = $this->getApplication()->getKernel()->getContainer()->get('doctrine')->getConnection();

        if ($connection->isConnected()) {
            $connection->close();
        }
    }

    /**
     * Purge PHPCR content repository
     */
    protected function cleanPhpcr()
    {
        $application = $this->getApplication();
        $commandInput = new ArrayInput(array(
            'command'   => 'doctrine:phpcr:workspace:purge',
            '--force'   => '--force'
        ));
        $application->doRun($commandInput, $this->output);
    }
}
