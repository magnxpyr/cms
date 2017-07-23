<?php
/**
 * @copyright   2006 - 2017 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Engine\Behavior;

use Engine\Meta;
use Phalcon\DI;

/**
 * Dependency container trait.
 * @package Engine\Behavior
 */
trait DiBehavior
{
    use Meta;

    /**
     * Dependency injection container.
     * @var DI|Meta
     */
    public $di;

    /**
     * Set DI.
     * @param \Phalcon\DiInterface $di
     * @return void
     */
    public function setDI($di)
    {
        $this->di = $di;
    }

    /**
     * Set DI.
     * @return DI|Meta
     */
    public function getDI()
    {
        if ($this->di == null) {
            $di = Di::getDefault();
            $this->setDI($di);
        }
        return $this->di;
    }
}