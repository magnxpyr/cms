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

use Phalcon\Web\Tools;
define('DS', DIRECTORY_SEPARATOR);
define('ROOT_PATH', dirname(__DIR__) . DS);
define('APP_PATH', ROOT_PATH . 'app/');
define('MEDIA_PATH', ROOT_PATH . 'media/');
define('PUBLIC_PATH', __DIR__ . DS);

require 'webtools.config.php';

require_once APP_PATH . 'vendor/phalcon/pretty-exceptions/loader.php';
/*
require PTOOLSPATH . '/scripts/Phalcon/Web/Tools.php';

Tools::main(PTOOLSPATH, PTOOLS_IP);
*/
require_once APP_PATH . 'engine/Bootstrap.php';

$bootstrap = new Bootstrap();
$bootstrap->run();