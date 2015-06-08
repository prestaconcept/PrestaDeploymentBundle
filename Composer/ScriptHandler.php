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
     * Install all the configuration files used by Prestaconcept Jenkins and create your build.xml
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

        self::copyBuildConfigFiles($event);

        $buildData = file_get_contents(__DIR__.'/../Resources/skeleton/build.xml.dist');

        $projectName = $event->getIO()->ask(
            'Your jenkins project name: ',
            'your-project-name'
        );
        $consolePath = $event->getIO()->ask(
            'Location of console file (default: app/console): ',
            'app/console'
        );

        $buildData = str_replace('###PROJECT_NAME###', $projectName, $buildData);
        $buildData = str_replace('###CONSOLE_PATH###', $consolePath, $buildData);

        $fs = new Filesystem();
        $fs->dumpFile($buildFile, $buildData);

        //Create jenkins parameters file
        file_put_contents(
            'app/config/parameters.yml.jenkins.dist',
            file_get_contents('app/config/parameters.yml.dist')
        );
        $event->getIO()->write('Please configure your jenkins parameters file');

        $event->getIO()->write('[presta-deployment] Install build configuration files done');
    }

    /**
     * Update all the configuration files used by Prestaconcept Jenkins
     *
     * Should be declared in your composer.json file like this :
     *
     * "scripts": {
     *     "post-update-cmd": [
     *         ...
     *         "Presta\\DeploymentBundle\\Composer\\ScriptHandler::updateBuildConfiguration"
     *     ],
     * ...
     * @param CommandEvent $event
     */
    public static function updateBuildConfiguration(CommandEvent $event)
    {
        $event->getIO()->write('[presta-deployment] Update build configuration files');

        self::copyBuildConfigFiles($event);

        $event->getIO()->write('[presta-deployment] Update build configuration files done');
    }

    /**
     * Mirror copy skeleton/build-config files into project
     *
     * @param CommandEvent $event
     */
    protected static function copyBuildConfigFiles(CommandEvent $event)
    {
        $rootDir = getcwd();
        $fs = new Filesystem();
        $fs->mirror(
            self::getSkeletonPath() . 'build-config',
            $rootDir . '/build-config',
            null,
            array('override' => true)
        );
    }

    /**
     * @return string
     */
    protected static function getSkeletonPath()
    {
        return __DIR__ . '/../Resources/skeleton/';
    }
}
