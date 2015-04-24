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

namespace Tools\Builder;

use Phalcon\Text as Utils;

/**
 * \Phalcon\Builder\Controller
 *
 * Builder to generate controller
 *
 * @category 	Phalcon
 * @package 	Builder
 * @copyright   Copyright (c) 2011-2014 Phalcon Team (team@phalconphp.com)
 * @license 	New BSD License
 */
class Controller extends Component
{

    /**
     * Controller constructor
     *
     * @param $options
     * @throws \Exception
     */
    public function __construct($options)
    {
        if (!isset($options['name'])) {
            throw new \Exception("Please specify the controller name");
        }
        if (!isset($options['force'])) {
            $options['force'] = false;
        }
        $this->_options = $options;
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function build()
    {
        $path = '';
        if (isset($this->_options['directory']) && $this->_options['directory']) {
            $path = $this->_options['directory'] . '/';
            print_r($path);
        }

        if (isset($this->_options['namespace'])) {
            $namespace = 'namespace '.$this->_options['namespace'].';'.PHP_EOL.PHP_EOL;
        } else {
            $namespace = '';
        }

        if (isset($this->_options['baseClass'])) {
            $baseClass = $this->_options['baseClass'];
        } else {
            $baseClass = '\Phalcon\Mvc\Controller';
        }
        $this->_options['controllersDir'] = $path;
        if (!isset($this->_options['controllersDir'])) {
            $config = $this->_getConfig($path);
            if (!isset($config->application->controllersDir)) {
                throw new \Exception("Please specify a controller directory");
            }
            $controllersDir = $config->application->controllersDir;
        } else {
            $controllersDir = $this->_options['controllersDir'];
        }

        $name = $this->_options['name'];
        $name = trim($name);

        if (!$name) {
            throw new \Exception("The controller name is required");
        }

        $name = str_replace(' ','_',$name);

        $className = Utils::camelize($name);

        $controllerPath = $controllersDir . DIRECTORY_SEPARATOR . $className . "Controller.php";

        $code = "<?php\n\n".$namespace."class ".$className."Controller extends ".$baseClass."\n{\n\n\tpublic function indexAction() {\n\n\t}\n\n}\n\n";
        $code = str_replace("\t", "    ", $code);

        if (!file_exists($controllerPath) || $this->_options['force'] == true) {
            if (!@file_put_contents($controllerPath, $code)) {
                throw new \Exception("Unable to write to '$controllerPath'");
            }
        } else {
            throw new \Exception("The Controller '$name' already exists");
        }

        if ($this->isConsole()) {
            $this->_notifySuccess('Controller "' . $name . '" was successfully created.');
        }

        return $className . 'Controller.php';

    }

}
