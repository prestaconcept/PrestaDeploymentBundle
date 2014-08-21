<?php
/**
 * This file is part of the PrestaDeploymentBundle.
 *
 * (c) PrestaConcept <http://www.prestaconcept.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Presta\DeploymentBundle\Command\Phpcr;

use Exception;
use Presta\DeploymentBundle\Command\AbstractDeploymentCommand;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author David Gaussinel <dgaussinel@prestaconcept.net>
 */
class WorkspaceCreateCommand extends AbstractDeploymentCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this
            ->setName('presta:deployment:phpcr-create-workspace')
            ->setDescription('Create the workspace in the configured repository')
            ->setHelp(
                'The <info>presta:deployment:phpcr-create-workspace</info> command creates a workspace'
                . ' with the configured name for this session.' . PHP_EOL
                . 'It will fail if a workspace with that name already exists or if the repository implementation'
                . 'does not support the workspace creation operation.'. PHP_EOL
            );
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->init($input, $output);

        $session = $this->getContainer()->get('sonata.admin.manager.doctrine_phpcr')
            ->getDocumentManager()->getPhpcrSession();
        $workspaceName = $session->getWorkspace()->getName();

        try {
            $application = $this->getApplication();
            $commandInput = new ArrayInput(array(
                'command' => 'doctrine:phpcr:workspace:create',
                'name'    => $workspaceName
            ));
            $application->doRun($commandInput, $this->output);

        } catch (Exception $e) {
            $output->writeln("Can't create workspace, guess that already exists");
        }
    }
}
