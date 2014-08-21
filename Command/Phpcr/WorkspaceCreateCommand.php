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
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author David Gaussinel <dgaussinel@prestaconcept.net>
 */
class WorkspaceCreateCommand extends ContainerAwareCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this
            ->setName('presta:deployment:phpcr-create-workspace')
            ->setDescription('Create the workspace in the configured repository')
            ->setHelp(<<<EOT
The <info>presta:deployment:phpcr-create-workspace</info> command creates a workspace
with the configured name for this session.
It will fail if a workspace with that name already exists or if the repository implementation
does not support the workspace creation operation.
EOT
            );
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $session = $this->getContainer()->get('sonata.admin.manager.doctrine_phpcr')
            ->getDocumentManager()->getPhpcrSession();
        $workspaceName = $session->getWorkspace()->getName();
        $command = $this->getApplication()->find('doctrine:phpcr:workspace:create');

        $arguments = array(
            'command' => 'doctrine:phpcr:workspace:create',
            'name'    => $workspaceName
        );

        $input = new ArrayInput($arguments);

        try {
            $command->run($input, $output);
        } catch (Exception $e) {
            $output->writeln("Can't create workspace, guess that already exists");
        }
    }
}
