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
class UpdateCommand extends AbstractDeploymentCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('presta:deployment:update')
            ->setDescription('Update your environment');
    }

    /**
     * {@inheritdoc}
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $this->init($input, $output);

        $this->log('Update ..');

        if ($this->getConfigurationManager()->isMigrationEnabled()) {
            $this->handleMigrations($input, $output);
        }
        if ($this->getConfigurationManager()->isPhpcrEnabled()) {
            $this->updatePhpcr();
        }

        $this->log('Update done..');
    }

    /**
     * Run doctrine migrations
     */
    protected function handleMigrations()
    {
        $application  = $this->getApplication();
        $commandInput = new ArrayInput(array('command' => 'doctrine:migrations:migrate'));
        $commandInput->setInteractive(false);
        $application->doRun($commandInput, $this->output);
    }

    /**
     * Handle PHPCR content repository update
     */
    protected function updatePhpcr()
    {
        $this->log('Update PHPCR ..');

        //Update system nodes
        $application = $this->getApplication();
        $commandInput = new ArrayInput(array('command' => 'doctrine:phpcr:repository:init'));
        $application->doRun($commandInput, $this->output);

        $this->log('Update PHPCR done..');
    }
}
