<?php
/**
 * @copyright   2006 - 2015 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Core\Models;

use Phalcon\Mvc\Model;
use Sb\Framework\Mvc\Model\EagerLoadingTrait;

/**
 * Class UserResetPasswords
 * @package Core\Models
 */
class UserResetPasswords extends Model
{
    use EagerLoadingTrait;

    /**
     * Temporary variable to store raw token
     * @var string
     */
    private $rawToken;

    /**
     * @var integer
     */
    protected $id;

    /**
     * @var integer
     */
    protected $user_id;

    /**
     * @var string
     */
    protected $token;

    /**
     * @var integer
     */
    protected $expires;

    /**
     * Method to set the value of field id
     *
     * @param integer $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Method to set the value of field user_id
     *
     * @param integer $user_id
     * @return $this
     */
    public function setUserId($user_id)
    {
        $this->user_id = $user_id;

        return $this;
    }

    /**
     * Method to set the value of field token
     *
     * @param string $token
     * @return $this
     */
    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Method to set the value of field expires
     *
     * @param integer $expires
     * @return $this
     */
    public function setExpires($expires)
    {
        $this->expires = $expires;

        return $this;
    }

    /**
     * Returns the value of field id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns the value of field user_id
     *
     * @return integer
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * Returns the value of field token
     *
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Returns the value of field expires
     *
     * @return integer
     */
    public function getExpires()
    {
        return $this->expires;
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSource('user_reset_passwords');
        $this->belongsTo('user_id', 'Core\Models\User', 'id', ['alias' => 'user', 'reusable' => true]);
    }

    public function getSource()
    {
        return 'user_reset_passwords';
    }

    /**
     * After create the user create a confirmation token
     */
    public function beforeValidation()
    {
        $this->rawToken = $this->getDI()->getShared('security')->generateToken();
        $this->setExpires(time() + 24 * 60);
        $this->setToken(hash('sha256', $this->rawToken));
    }

    /**
     * Send an e-mail to users allowing them to activate their account or reset the password
     */
    public function afterSave()
    {
        $params = [
            'siteName' => $this->getDI()->getShared('config')->app->siteName,
            'name' => $this->user->getUsername(),
            'resetUrl' => $this->getDI()->getShared('url')->getUri(
                'user/forgot-password',
                true,
                '?code=' . $this->rawToken
            )
        ];
        $this->getDI()->get('mail')->createMessageFromView('resetPassword', $params)
        ->to($this->user->getEmail())
        ->subject('Password Change Request')
        ->send();
    }
}