<?php
/**
 * @copyright   2006 - 2017 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Engine\Acl;

use Engine\Behavior\AclBehavior;
use Phalcon\Acl\Adapter\Memory as AclMemory;

/**
 * Class BaseMemory
 * @package Engine\Acl
 */
class BaseMemory extends AclMemory
{
    use AclBehavior;

    /**
     * @var \Engine\Acl\Database
     */
    public $adapter;

    /**
     * Check if current user has access to view
     *
     * @param $roles
     * @return bool
     */
    public function checkViewLevel($roles)
    {
        $allow = false;
        if (in_array($this->getDI()->get('auth')->getUserRole(), $roles))
            $allow = true;

        return $allow;
    }

    public function getRoleByKey($id) {
        $roles = $this->getDI()->get('acl')->getRoles();
        foreach ($roles as $key => $role) {
            if ((int)$id == $key + 1) {
                return $role->getName();
            }
        }
        return 'guest';
    }
}