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

use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 */
class RebuildCommand extends InstallCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('presta:deployment:rebuild')
            ->setDescription('Rebuild your environment');
    }

    /**
     * {@inheritdoc}
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $this->init($input, $output);

        $this->log('Rebuild ..');

        if ($this->getConfigurationManager()->isOrmEnabled()) {
            $this->rebuildOrm();
        }
        if ($this->getConfigurationManager()->isPhpcrEnabled()) {
            $this->rebuildPhpcr();
        }

        $this->log('Rebuild done..');
    }

    /**
     * Drop ORM database and install
     */
    protected function rebuildOrm()
    {
        $application = $this->getApplication();
        $commandInput = new ArrayInput(array('command'=>'doctrine:database:drop', '--force' => '--force'));
        $application->doRun($commandInput, $this->output);

        $connection = $this->getApplication()->getKernel()->getContainer()->get('doctrine')->getConnection();

        if ($connection->isConnected()) {
            $connection->close();
        }

        $this->installOrm();
    }

    /**
     * Purge PHPCR content repository and install
     */
    protected function rebuildPhpcr()
    {
        $application = $this->getApplication();
        $commandInput = new ArrayInput(array('command'=>'doctrine:phpcr:workspace:purge', '--force' => '--force'));
        $application->doRun($commandInput, $this->output);

        $this->installPhpcr();
    }
}
