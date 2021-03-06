<?php
/*
  +------------------------------------------------------------------------+
  | Phalcon Developer Tools                                                |
  +------------------------------------------------------------------------+
  | Copyright (c) 2011-2014 Phalcon Team (http://www.phalconphp.com)       |
  +------------------------------------------------------------------------+
  | This source file is subject to the New BSD License that is bundled     |
  | with this package in the file docs/LICENSE.txt.                        |
  |                                                                        |
  | If you did not receive a copy of the license and are unable to         |
  | obtain it through the world-wide-web, please send an email             |
  | to license@phalconphp.com so we can send you a copy immediately.       |
  +------------------------------------------------------------------------+
  | Authors: Andres Gutierrez <andres@phalconphp.com>                      |
  |          Eduar Carvajal <eduar@phalconphp.com>                         |
  +------------------------------------------------------------------------+
*/

namespace Module\Tools\Controllers;

use Engine\Mvc\AdminController;
use Module\Tools\Helpers\Tools;

/**
 * Class ControllerBase
 * @package Tools\Controllers
 */
class ControllerBase extends AdminController
{
    /**
     * Initialize controller
     * @throws \Exception
     */
    public function initialize()
    {
        parent::initialize();
        $this->_checkAccess();
    }

    /**
     * Check remote IP address to disable remote activity
     *
     * @return void
     * @throws \Exception if connected remotely
     */
    protected function _checkAccess()
    {
        $ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : false;

        if ($ip && ($ip == '127.0.0.1' || $ip == '::1' || $this->checkToolsIp($ip)))
            return;

        throw new \Exception('WebTools can only be used on the local machine (Your IP: ' . $ip . ') or you can make changes in your configuration file to allow IP or NET');
    }

    /**
     * List database tables
     *
     * @param  bool $all
     * @return void
     */
    protected function listTables($all = false)
    {
        $config = Tools::getConfig();
        $connection = Tools::getConnection();

        if ($all) {
            $tables = array('all' => 'All');
        } else {
            $tables = array();
        }

        $dbTables = $connection->listTables();
        foreach ($dbTables as $dbTable) {
            $tables[$dbTable] = $dbTable;
        }

        $this->view->tables = $tables;
        if ($config->dbAdaptor != 'Sqlite') {
            $this->view->databaseName = $config->dbName;
        } else {
            $this->view->databaseName = null;
        }
    }

    /**
     * Check if IP address for securing Developers Tools area matches the given
     *
     * @param  string $ip
     * @return bool
     */
    private function checkToolsIp($ip)
    {
        $allowedIp = Tools::getToolsIp();
        if (is_array($allowedIp)) {
            $allowedIp = implode(',', (array)$allowedIp);
        }
        return strpos($allowedIp, $ip) !== 0;
    }
}
