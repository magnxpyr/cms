<?php
/**
 * @copyright   2006 - 2019 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Module\Core\Controllers;

use Module\Core\Forms\ConfirmEmailForm;
use Module\Core\Forms\ForgotPasswordForm;
use Module\Core\Forms\LoginForm;
use Module\Core\Forms\RegisterForm;
use Module\Core\Forms\ResetPasswordForm;
use Module\Core\Models\User;
use Module\Core\Models\UserEmailConfirmations;
use Module\Core\Models\UserResetPasswords;
use Engine\Mvc\Auth;
use Engine\Mvc\Controller;

/**
 * Class UserController
 * @package Module\Core\Controllers
 */
class UserController extends Controller
{
    public function indexAction()
    {

    }

    /**
     * Login user
     */
    public function loginAction()
    {
        $form = new LoginForm();
        try {
            $this->auth->login($form);
        } catch (\Exception $e) {
            $this->flash->error($e->getMessage());
        }
        $this->view->setVar('form', $form);
        $this->view->setVar('title', $this->t->_("User login"));
    }

    /**
     * Login with Facebook account
     */
    public function loginWithFacebookAction()
    {
        try {
            $this->view->disable();
            $this->auth->loginWithFacebook();
        } catch(\Exception $e) {
            $this->flashSession->error($this->t->_('There was an error connecting to %name%', ['name' => 'Facebook']));
            $this->auth->redirectReturnUrl();
        }
    }

    /**
     * Login with Google account
     */
    public function loginWithGoogleAction()
    {
        try {
            $this->view->disable();
            $this->auth->loginWithGoogle();
        } catch(\Exception $e) {
            $this->flashSession->error($this->t->_('There was an error connecting to %name%', ['name' => 'Google']));
            $this->auth->redirectReturnUrl();
        }
    }

    /**
     * Register user account
     */
    public function registerAction()
    {
        if($this->auth->isUserSignedIn()) {
            return $this->auth->redirectReturnUrl();
        }
        $form = new RegisterForm();
        if ($this->request->isPost()) {
            if($form->isValid($_POST)) {
                $username = $this->request->getPost('username', 'alphanum');
                $email = $this->request->getPost('email', 'email');
                $password = $this->request->getPost('password', 'string');
                $repeatPassword = $this->request->getPost('repeatPassword', 'string');
                if ($password != $repeatPassword) {
                    $this->flash->error($this->t->_('Passwords don\'t match'));
                }
                $user = new User();
                $user->setUsername($username);
                $user->setPassword($this->security->hash($password));
                $user->setEmail($email);
                $user->setRoleId(1);
                $user->setStatus(0);
                $user->setResetToken($this->security->getRandom()->hex(Auth::TOKEN_BYTES));
                $user->setCreatedAt(time());
                if (!$user->save()) {
                    $this->flashErrors($user);
                } else {
                    // send email
                    $form->clear();
                    $this->flash->success($this->t->_('Thanks for signing up. An email has been sent to activate your account.'));
                }
            } else {
                $this->flashErrors($form);
            }
        }
        $this->view->setVar('form', $form);
        $this->view->setVar('title', $this->t->_("User register"));
    }

    /**
     * Remove user session
     * @return \Phalcon\Http\Response
     */
    public function logoutAction()
    {
        $this->view->disable();
        $this->flashSession->success($this->t->_('You have been logged out successfully'));
        return $this->auth->remove();
    }

    /**
     * Confirms an email
     */
    public function confirmEmailAction()
    {
        $this->setTitle('Request email confirmation', true);
        $form = new ConfirmEmailForm();
        $this->view->setVar('form', $form);

        // If Post, user exist and not active, generate a new token
        if ($this->request->isPost()) {
            if($form->isValid($_POST)) {
                $user = User::findFirstByEmail($this->request->get('email', 'email'))->load('userEmailConfirmations');
                $form->clear();
                if($user) {
                    if($user->getStatus() > 0) {
                        $this->flashSession->notice('Your account is already active');
                        $this->response->redirect('user/login')->send();
                        return;
                    }
                    if (!$user->userEmailConfirmations) {
                        $confirmation = new UserEmailConfirmations();
                        $confirmation->setUserId($user->getId());
                        $confirmation->save();
                    } else {
                        $user->userEmailConfirmations->setExpires(1);
                        $user->userEmailConfirmations->save();
                    }
                }
                $this->flash->success('Email sent. Please follow the instructions to activate your account');
                $this->response->redirect('index/index')->send();
                return;
            } else {
                $this->flashErrors($form);
            }
            return;
        }

        $error = 'An unexpected error occurred during your email confirmation. Please try again later or request a new email confirmation';

        // if no code on url, we show the form
        $code = $this->request->get('code', 'alphanum');
        if(!$code) {
            $this->flash->error($error);
            return;
        }

        $confirmation = UserEmailConfirmations::findFirstByToken(hash('sha256', $code));
        if (!$confirmation) {
            $this->flash->error($error);
            return;
        }

        // if token expired, error
        if($confirmation->getExpires() < time()) {
            $confirmation->delete();
            $this->flash->error($error);
            return;
        }

        // Change the confirmation to 'confirmed' and update the user to 'active'
        $confirmation->user->status = 1;

        if (!$confirmation->user->save()) {
            $this->flashErrors($confirmation->user);
            $this->view->status = 0;
            return;
        }

        // delete the token
        $confirmation->delete();

        $this->flash->success('Your email was successfully confirmed. Please login to manage your account');
        $this->response->redirect('user/login')->send();
        return;
    }

    /**
     * Request reset password
     */
    public function forgotPasswordAction()
    {
        if($this->auth->isUserSignedIn()) {
            return $this->auth->redirectReturnUrl();
        }

        $this->view->status = 1;

        $form = new ForgotPasswordForm();
        $this->view->setVar('form', $form);

        // Request password reset
        if ($this->request->isPost()) {
            if ($form->isValid($_POST)) {
                $user = User::findFirstByEmail($this->request->get('email', 'email'))->load('userResetPasswords');
                $form->clear();
                if ($user) {
                    if (!$user->userResetPasswords) {
                        $reset = new UserResetPasswords();
                        $reset->setUserId($user->getId());
                        $reset->save();
                    } else {
                        $user->userResetPasswords->setExpires(1);
                        $user->userResetPasswords->save();
                    }
                }
                $this->flashSession->success('Email sent. Please follow the instructions to change your password');
                $this->response->redirect('index/index')->send();
                return;
            } else {
                $this->flashErrors($form);
            }
            return;
        }

        $code = $this->request->get('code', 'alphanum');

        $error = 'An unexpected error occurred. Please try again later or request a new password change';

        // Validate token and
        if($code) {
            $resetPassword = UserResetPasswords::findFirstByToken(hash('sha256', $code));
            if (!$resetPassword) {
                $this->flash->error($error);
                return;
            }
            if ($resetPassword->getExpires() < time()) {
                $resetPassword->delete();
                $this->flash->error($error);
                return;
            }

            try {
                $this->auth->authUserById($resetPassword->getUserId());
            } catch (\Exception $e) {
                $this->flash->error($e->getMessage());
            }

            $resetPassword->delete();

            $this->response->redirect('user/resetPassword')->send();
            return;
        }
    }

    /**
     * Reset user password
     */
    public function resetPasswordAction()
    {
        if (!$this->auth->isUserSignedIn()) {
            $this->response->redirect('user/login')->send();
            return;
        }

        $form = new ResetPasswordForm();
        $this->view->form = $form;

        if ($this->request->isPost()) {
            if ($form->isValid($_POST)) {
                $password = $this->request->getPost('password', 'string');
                $repeatPassword = $this->request->getPost('repeatPassword', 'string');
                if ($password != $repeatPassword) {
                    $this->flash->error($this->t->_('Passwords don\'t match'));
                    return;
                }
                $user = User::findFirstById($this->auth->getUserId());
                $user->setPassword($this->security->hash($password));

                // Set a new password
                if (!$user->save()) {
                    $this->flashErrors($user);
                    $this->response->redirect('index/index')->send();
                    return;
                }

                $this->flashSession->success($this->t->_('Password changed successfully'));
                $this->response->redirect('index/index')->send();
                return;
            } else {
                $this->flashErrors($form);
            }
        }
    }
}