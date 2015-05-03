<?php
/**
 * @copyright   2006 - 2015 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @url         http://www.magnxpyr.com
 * @authors     Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Engine;

use Phalcon\DiInterface;
use Phalcon\Mvc\ModuleDefinitionInterface;
use Phalcon\Text;

abstract class Module implements ModuleDefinitionInterface {

    /**
     * Registers an autoloader related to the module
     *
     * @param \Phalcon\DiInterface $di
     */
    public function registerAutoloaders(DiInterface $di = null) {
    }

    /**
     * Registers an autoloader related to the module
     *
     * @param \Phalcon\DiInterface $di
     */
    public function registerServices(DiInterface $di) {
        $moduleName = Text::camelize($di['router']->getModuleName());

        //Attach a event listener to the dispatcher
        $dispatcher = $di->get('dispatcher');
        $dispatcher->setDefaultNamespace($moduleName . '\Controllers');

        //Registering the view component
        $view = $di->get('view');
        $view->setViewsDir(APP_PATH . 'modules/' . $moduleName . '/Views/');
    }
}