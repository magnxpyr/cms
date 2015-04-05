<?php
/**
 * @copyright   2006 - 2015 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @url         http://www.magnxpyr.com
 */

namespace Engine;

class Loader extends \Phalcon\Loader {

    public function init($namespaces) {
        // Phalcon loader
        $this->registerNamespaces(array_merge($namespaces->toArray(), array(
            'Phalcon' => APP_PATH . 'vendor/phalcon/incubator/Library/Phalcon/',
            'Engine' => APP_PATH . 'engine/'
        )));
        $this->register();


        // Composer loader
        require_once APP_PATH . 'vendor/autoload.php';
    }

    public function modulesConfig($modules_list)
    {
        $namespaces = array();
        $modules = array();
        if (!empty($modules_list)) {
            foreach ($modules_list as $module) {
                $namespaces["Modules\\$module"] = APP_PATH . 'modules/' . $module;
                $modules[$module] = array(
                    'className' => "Modules\\$module\\Module",
                    'path' => APP_PATH . "modules/$module/Module.php"
                );
            }
        }

        $modules_array = array(
            'loader' => array('namespaces' => $namespaces),
            'modules' => $modules,
        );

        return $modules_array;
    }
}