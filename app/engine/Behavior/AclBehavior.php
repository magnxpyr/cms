<?php
/**
 * @copyright   2006 - 2017 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Engine\Behavior;

/**
 * Class AclBehavior
 * @package Engine\Behavior
 */
trait AclBehavior
{
    use DiBehavior;

    /**
     * @var \Phalcon\Acl
     */
    private $acl;

    /**
     * Acl cache key.
     * @var string
     */
    private $cacheKey = "acl_data";

    /**
     * Acl cache lifetime
     * @var int
     */
    private $cacheExpire = 2592000;

    /**
     * @return string
     */
    public function getCacheKey()
    {
        return $this->cacheKey;
    }

    /**
     * @return int
     */
    public function getCacheExpire()
    {
        return $this->cacheExpire;
    } // 30 days cache.


}