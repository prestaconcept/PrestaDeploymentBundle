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
class InstallCommand extends AbstractDeploymentCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('presta:deployment:install')
            ->setDescription('Install your environment');
    }

    /**
     * {@inheritdoc}
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $this->init($input, $output);

        if ($this->getConfigurationManager()->isOrmEnabled()) {
            $this->installOrm();
        }
        if ($this->getConfigurationManager()->isPhpcrEnabled()) {
            $this->installPhpcr();
        }
    }

    /**
     * Handle ORM database installation
     */
    protected function installOrm()
    {
        $this->log('Install ORM ..');

        $application = $this->getApplication();
        $commandInput = new ArrayInput(array('command'=>'doctrine:database:create'));
        $application->doRun($commandInput, $this->output);

        $commandInput = new ArrayInput(array('command'=>'doctrine:schema:create'));
        $application->doRun($commandInput, $this->output);

        $commandInput = new ArrayInput(array('command'=>'doctrine:fixture:load', '--append' => true));
        $application->doRun($commandInput, $this->output);

        $this->log('Install ORM done..');
    }

    /**
     * Handle PHPCR content repository installtion
     */
    protected function installPhpcr()
    {
        $this->log('Install PHPCR ..');

        $application = $this->getApplication();
        $commandInput = new ArrayInput(array('command'=>'presta:deployment:phpcr-create-workspace'));
        $application->doRun($commandInput, $this->output);

        $commandInput = new ArrayInput(array('command'=>'doctrine:phpcr:repository:init'));
        $application->doRun($commandInput, $this->output);

        $commandInput = new ArrayInput(array('command'=>'doctrine:phpcr:fixtures:load', '--append' => true));
        $application->doRun($commandInput, $this->output);

        $this->log('Install PHPCR done..');
    }
}
