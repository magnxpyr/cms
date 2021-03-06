<?php
/**
 * @copyright   2006 - 2019 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Engine\Di;

use Engine\Meta;
use Phalcon\DI;
use Phalcon\DiInterface;

/**
 * Dependency container trait.
 * @package Engine\Behavior
 */
trait DiTrait
{
    use Meta;

    /**
     * Dependency injection container.
     * @var DI|Meta
     */
    private $di;

    /**
     * Set DI.
     * @param \Phalcon\DiInterface $di
     * @return void
     */
    public function setDI(DiInterface $di)
    {
        $this->di = $di;
    }

    /**
     * Set and return DI.
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