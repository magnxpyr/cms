<?php
/**
 * @copyright   2006 - 2017 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Engine\Mvc;

use Engine\Meta;
use Module\Core\Models\Content;
use Module\Core\Models\User;
use Phalcon\Mvc\User\Component;
use Phalcon\Text;

/**
 * Class Helper
 * @package Engine\Mvc
 */
class Helper extends Component
{
    use Meta;

    private $userStatuses = [
        User::STATUS_ACTIVE => 'Active',
        User::STATUS_INACTIVE => 'Inactive',
        User::STATUS_BLOCKED => 'Blocked'
    ];

    private $articleStatuses = [
        Content::STATUS_PUBLISHED => 'Published',
        Content::STATUS_UNPUBLISHED => 'Unpublished',
        Content::STATUS_TRASHED => 'Trashed'
    ];

    /**
     * Get user status by id
     * @param $id
     * @return mixed
     */
    public function getUserStatus($id)
    {
        return $this->userStatuses[$id];
    }

    /**
     * Get possible statuses for users
     * @return array
     */
    public function getUserStatuses()
    {
        return $this->userStatuses;
    }

    /**
     * Get article status by id
     * @param $id
     * @return mixed
     */
    public function getArticleStatus($id)
    {
        return $this->articleStatuses[$id];
    }

    /**
     * Get possible statuses for articles
     * @return array
     */
    public function getArticleStatuses()
    {
        return $this->articleStatuses;
    }

    /**
     * Return full url
     *
     * @param string $path
     * @param bool|true $get
     * @param string|null $params
     * @return string
     */
    public function getUri($path, $get = true, $params = null)
    {
        $protocol  = 'http://';
        if ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443) {
            $protocol = 'https://';
        }
        if($get) {
            $path = $this->url->get($path);
        }
        if($params !== null) {
            $path .= $params;
        }
        return $protocol . $_SERVER['HTTP_HOST'] . $path;
    }

    /**
     * @param string $string
     * @return string
     */
    public function makeAlias($string)
    {
        return strtolower(str_replace(" ", "-", $string));
    }

    /**
     * Check if is an admin page
     * @return bool
     */
    public function isBackend()
    {
        $isBackend = false;
        if ($this->router->getMatchedRoute() != null && strpos($this->router->getMatchedRoute()->getName(), 'admin') !== false)
            $isBackend = true;

        return $isBackend;
    }

    /**
     * Convert array to object
     * @param $data
     * @return \stdClass
     */
    public function arrayToObject($data){
        $obj = new \stdClass();

        foreach($data as $key => $val){
            $obj->{$key} = $val;
        }

        return $obj;
    }

    /**
     * Uncamelize and replace _ with -
     * @param $str
     * @return mixed
     */
    public function uncamelize($str)
    {
        return str_replace('_', '-', Text::uncamelize($str));
    }

    /**
     * Decode html entity
     * @param $str
     * @return mixed
     */
    public function htmlDecode($str)
    {
        return html_entity_decode($str);
    }

    /**
     * @param $dir
     * @return bool
     */
    public function removeDir($dir) {
        $files = array_diff(scandir($dir), array('.','..'));
        foreach ($files as $file) {
            is_dir("$dir/$file") ? $this->removeDir("$dir/$file") : unlink("$dir/$file");
        }
        try {
            $removed = rmdir($dir);
        } catch (Exception $e) {
            $this->logger->error("Can't remove directory " . $dir);
            $removed = false;
        }
        return $removed;
    }
}