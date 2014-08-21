<?php
/**
 * This file is part of the PrestaDeploymentBundle.
 *
 * (c) PrestaConcept <http://www.prestaconcept.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Presta\DeploymentBundle\Manager;

/**
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 */
class ConfigurationManager
{
    /**
     * @var boolean
     */
    protected $ormEnabled = false;

    /**
     * @var boolean
     */
    protected $phpcrEnabled = false;

    /**
     * @var boolean
     */
    protected $migrationEnabled = false;

    /**
     * @var boolean
     */
    protected $deployRebuildEnabled = false;

    /**
     * @param boolean $ormEnabled
     */
    public function setOrmEnabled($ormEnabled)
    {
        $this->ormEnabled = $ormEnabled;
    }

    /**
     * @return boolean
     */
    public function isOrmEnabled()
    {
        return $this->ormEnabled;
    }

    /**
     * @param boolean $phpcrEnabled
     */
    public function setPhpcrEnabled($phpcrEnabled)
    {
        $this->phpcrEnabled = $phpcrEnabled;
    }

    /**
     * @return boolean
     */
    public function isPhpcrEnabled()
    {
        return $this->phpcrEnabled;
    }

    /**
     * @param boolean $migrationEnabled
     */
    public function setMigrationEnabled($migrationEnabled)
    {
        $this->migrationEnabled = $migrationEnabled;
    }

    /**
     * @return boolean
     */
    public function isMigrationEnabled()
    {
        return $this->migrationEnabled;
    }

    /**
     * @param boolean $deployRebuildEnabled
     */
    public function setDeployRebuildEnabled($deployRebuildEnabled)
    {
        $this->deployRebuildEnabled = $deployRebuildEnabled;
    }

    /**
     * @return boolean
     */
    public function isDeployRebuildEnabled()
    {
        return $this->deployRebuildEnabled;
    }
}
