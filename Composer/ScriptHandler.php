<?php
/**
 * This file is part of the PrestaDeploymentBundle
 *
 * (c) PrestaConcept <www.prestaconcept.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Presta\DeploymentBundle\Composer;

use Composer\Script\CommandEvent;
use Symfony\Component\Filesystem\Filesystem;

/**
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 */
class ScriptHandler
{
    /**
     * Install all the configuration files used by Prestaconcept Jenkins
     *
     * Should be declared in your composer.json file like this :
     *
     * "scripts": {
     *     "post-install-cmd": [
     *         ...
     *         "Presta\\DeploymentBundle\\Composer\\ScriptHandler::installBuildConfiguration"
     *     ],
     * ...
     *
     * @param CommandEvent $event
     */
    public static function installBuildConfiguration(CommandEvent $event)
    {
        $event->getIO()->write('[presta-deployment] Install build configuration files');

        $rootDir = getcwd();
        $buildFile = $rootDir . '/build.xml';

        if (file_exists($buildFile)) {
            $event->getIO()->write('[presta-deployment] Build configuration already exist : abort');
            return;
        }

        $fs = new Filesystem();
        $fs->mirror(__DIR__.'/../Resources/skeleton/build-config', $rootDir . '/build-config', null, array('override'));

        $buildData = file_get_contents(__DIR__.'/../Resources/skeleton/build.xml.dist');

        $fs->dumpFile($buildFile, $buildData);

        $event->getIO()->write('[presta-deployment] Install build configuration files done');
    }
}
