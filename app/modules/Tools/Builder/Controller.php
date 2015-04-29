<?php
/**
 * @copyright   2006 - 2015 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @url         http://www.magnxpyr.com
 * @authors     Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Tools\Builder;

use Phalcon\Text;
use Tools\Helpers\Tools;

class Controller extends Component {

    /**
     * Controller constructor
     *
     * @param array $options
     * @throws \Exception
     */
    public function __construct($options)
    {
        if (empty($options['name'])) {
            throw new \Exception("Please specify the controller name");
        }
        if (empty($options['force'])) {
            $options['force'] = false;
        }
        if (empty($options['directory'])) {
            $options['directory'] = Tools::getModulesPath() . $options['module'] .DIRECTORY_SEPARATOR. Tools::getControllersDir();
        }
        if (empty($options['namespace']) || $options['namespace'] != 'None') {
            $options['namespace'] = $options['module'] . '\\' . Tools::getControllersDir();
        }
        if (empty($options['baseClass'])) {
            $options['baseClass'] = '\Phalcon\Mvc\Controller';
        }
        $this->_options = $options;
    }

    /**
     * Build the controller
     *
     * @return string
     * @throws \Exception
     */
    public function build() {

        if ($this->_options['namespace'] != 'None') {
            $namespace = 'namespace '.$this->_options['namespace'].';'.PHP_EOL.PHP_EOL;
        } else {
            $namespace = '';
        }

        $controllerPath = $this->_options['directory'] . DIRECTORY_SEPARATOR . $this->_options['name'] . ".php";

        $base = explode('\\', $this->_options['baseClass']);
        $baseClass = end($base);

        $useClass = 'use '.$this->_options['baseClass'].';'.PHP_EOL.PHP_EOL;

        $code = "<?php\n".Tools::getCopyright()."\n\n".$namespace.$useClass."class ".$this->_options['name']." extends $baseClass {
            \n\tpublic function indexAction() {\n\n\t}\n}";
        $code = str_replace("\t", "    ", $code);

        if (!file_exists($controllerPath) || $this->_options['force'] == true) {
            if(!is_dir($this->_options['directory'])) {
                mkdir($this->_options['directory'], 0777, true);
                chmod($this->_options['directory'], 0777);
            }
            if (!@file_put_contents($controllerPath, $code)) {
                throw new \Exception("Unable to write to '$controllerPath'");
            }
            chmod($controllerPath, 0777);
        } else {
            throw new \Exception("The Controller '".$this->_options['name']."' already exists");
        }

        return $this->_options['name'] . '.php';
    }
}
