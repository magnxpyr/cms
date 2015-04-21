<?php
/**
 * @copyright   2006 - 2015 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @url         http://www.magnxpyr.com
 */

namespace Tools;

class Routes {

    public function init($router) {
        $router->add('/admin/tools/:controller/:action', array(
            'module' => 'tools',
            'controller' => 1,
            'index' => 2
        ));
    }

}