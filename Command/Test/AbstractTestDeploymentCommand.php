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
use Symfony\Bundle\FrameworkBundle\Console\Application;

/**
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 */
abstract class AbstractTestDeploymentCommand extends AbstractDeploymentCommand
{
    /**
     * @return Application
     */
    protected function getTestApplication()
    {
        $kernel = new \AppKernel('test', true);

        return new Application($kernel);
    }
}
