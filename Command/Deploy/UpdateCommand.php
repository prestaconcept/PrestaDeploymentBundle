<?php
/**
 * This file is part of the PrestaDeploymentBundle.
 *
 * (c) PrestaConcept <http://www.prestaconcept.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Presta\DeploymentBundle\Command\Deploy;

use Presta\DeploymentBundle\Command\AbstractDeploymentCommand;
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
            ->setName('presta:deployment:deploy-update')
            ->setDescription('Update your deploy target in production environment');
    }

    /**
     * {@inheritdoc}
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $this->init($input, $output);
        $this->checkEnvironment('prod');

        if ($this->getConfigurationManager()->isDeployRebuildEnabled()) {
            $this->rebuild();
        } else {
            $this->update();
        }
    }

    /**
     * Update : this should be used when project is in production mode
     */
    protected function update()
    {
        $this->log('Deploy update ..');

        $application = $this->getApplication();
        $commandInput = new ArrayInput(array('command' => 'presta:deployment:update'));
        $application->doRun($commandInput, $this->output);

        $this->log('Deploy update done..');
    }

    /**
     * Rebuild : this should be used when project is in development mode
     */
    protected function rebuild()
    {
        $this->log('Deploy rebuild ..');

        $application = $this->getApplication();
        $commandInput = new ArrayInput(array('command' => 'presta:deployment:rebuild'));
        $application->doRun($commandInput, $this->output);

        $this->log('Deploy rebuild done..');
    }
}
