<?php

namespace Dynart\Gears\Modules\User\src;

use Dynart\Minicore\Config;
use Dynart\Minicore\Database\Record;
use Dynart\Minicore\Framework;
use Dynart\Minicore\Mailer;
use Dynart\Minicore\Request;
use Dynart\Minicore\Response;
use Dynart\Minicore\Router;
use Dynart\Minicore\Session;

use Dynart\Gears\GearsApp;

use Dynart\Gears\Modules\User\src\Database\PermissionQuery;
use Dynart\Gears\Modules\User\src\Database\RoleQuery;
use Dynart\Gears\Modules\User\src\Database\UserHashQuery;
use Dynart\Gears\Modules\User\src\Database\UserHashTable;
use Dynart\Gears\Modules\User\src\Database\UserQuery;
use Dynart\Gears\Modules\User\src\Database\UserTable;

class UserService {

    // Core related
    /** @var Config */
    protected $config;

    /** @var GearsApp */
    protected $app;

    /** @var Session */
    protected $session;

    /** @var Mailer */
    protected $mailer;

    /** @var Request */
    protected $request;
    
    /** @var Response */
    protected $response;

    /** @var Router */
    protected $router;


    // DB related
    /** @var UserTable */
    protected $userTable;

    /** @var UserQuery */
    protected $userQuery;

    /** @var RoleQuery */
    protected $roleQuery;

    /** @var PermissionQuery */
    protected $permissionQuery;

    /** @var UserHashTable */
    protected $userHashTable;

    /** @var UserHashQuery */
    protected $userHashQuery;


    // User instances
    /** @var Record */
    protected $anonymousUser;

    /** @var Record */
    protected $currentUser;

    public function __construct() {

        $framework = Framework::instance();

        $this->config = $framework->get('config');
        $this->router = $framework->get('router');
        $this->session = $framework->get('session');
        $this->mailer = $framework->get('mailer');
        $this->request = $framework->get('request');
        $this->response = $framework->get('response');

        $this->userQuery = $framework->get('userQuery');
        $this->userTable = $framework->get('userTable');
        $this->roleQuery = $framework->get('roleQuery');
        $this->permissionQuery = $framework->get('permissionQuery');
        $this->userHashQuery = $framework->get('userHashQuery');
        $this->userHashTable = $framework->get('userHashTable');

        $this->app = $framework->get('app');

        $anonymousData = ['id' => 0, 'name' => 'Anonymous'];
        $this->anonymousUser = $framework->create(['\Dynart\Minicore\Database\Record', $anonymousData]);

        $this->rememberLogin();
    }
    
    public function getAvatarSize() {
        return $this->config->get(UserModule::CONFIG_AVATAR_SIZE, UserModule::DEFAULT_AVATAR_SIZE);
    }
    
    public function getAvatarMaxFileSize() {
        return $this->config->get(UserModule::CONFIG_AVATAR_MAX_FILE_SIZE, UserModule::DEFAULT_AVATAR_MAX_FILE_SIZE);
    }
    
    public function getAvatarQuality() {
        return $this->config->get(UserModule::CONFIG_AVATAR_QUALITY, UserModule::DEFAULT_AVATAR_QUALITY);
    }
    
    public function isRegisterDisabled() {
        return $this->config->get(UserModule::CONFIG_USERS_REGISTER_DISABLED);
    }

    public function getLoggedInUrl() {
        return $this->config->get(UserModule::CONFIG_LOGGED_IN_URL);
    }

    public function getLoggedOutUrl() {
        return $this->config->get(UserModule::CONFIG_LOGGED_OUT_URL);
    }

    public function hash($value) {
        return $this->userQuery->hash($value);
    }

    public function findById($id) {
        return $this->userQuery->findById($id);
    }

    private function findByHash(string $name, string $hash, bool $active=true) {
        $hashRecord = $this->userHashQuery->findOne(['user_id'], [
            'name' => $name,
            'hash' => $hash
        ]);
        if (!$hashRecord) {
            return null;
        }
        $options = ['id' => $hashRecord->get('user_id')];
        if ($active) {
            $options = ['active' => 1];
        }
        return $this->userQuery->findOne(null, $options);
    }

    private function getClientHash() {
        return md5($this->request->getIp().$this->request->getServer('HTTP_USER_AGENT'));
    }

    private function redirect(string $to) {
        /** @var GearsApp $app */
        $app = Framework::instance()->get('app');
        $app->redirect($to);
    }

    public function isLoggedIn() {
        return $this->session->get('hash') === $this->getClientHash();
    }

    public function setLoggedIn(int $userId) {
        $this->session->set('id', $userId);
        $this->session->set('hash', $this->getClientHash());
    }

    public function rememberLogin() {
        $rememberHash = $this->request->getCookie('remember_hash');
        if ($this->isLoggedIn() || !$rememberHash) {
            return;
        }
        $user = $this->findByHash('remember', $rememberHash);
        if (!$user) {
            return;
        }
        $this->doLogin($user);
    }
    
    public function requireLogin() {
        if ($this->isLoggedIn()) {
            return;
        }
        //$url = $this->router->getBaseUrl().$this->request->getUri();
        //$this->setLoginRedirectUrl($url);
        $this->redirect($this->getLoggedOutUrl());
    }

    /*
    public function requirePermission($permission) {
        $this->requireLogin();
        if (!$this->hasPermission($permission)) {
            $this->error(403);
        }
    }

    private function error(int $code) {
        $app = Framework::instance()->get('app');
        $app->error($code);
    }
    */
    
    public function login($email, $password, $remember) {
        $user = $this->userQuery->findOne(null, [
            'email' => $email,
            'password' => $password
        ]);
        if (!$user) {
            return false;
        }
        if ($remember) {
            $hash = $this->hash(time());
            $this->userHashTable->add($user->get('id'), 'remember', $hash);
            $this->response->setCookie('remember_hash', $hash);
        }
        $this->doLogin($user);
        return true;
    }

    /*
    public function getLoginRedirectUrl() {
        return $this->session->get('login_redirect_url');
    }
    
    public function setLoginRedirectUrl($value) {
        $this->session->set('login_redirect_url', $value);
    }
    */

    protected function doLogin(Record $user) {
        $user->set('last_login', date('Y-m-d H:i:s'));
        $this->userTable->save($user);
        $this->setLoggedIn($user->get('id'));
    }

    public function getCurrentId() {
        return $this->session->get('id', 0);
    }

    /**
     * @return Record
     */
    public function getCurrentUser() {
        if (!$this->isLoggedIn()) {
            return $this->anonymousUser;
        }
        if ($this->currentUser) {
            return $this->currentUser;
        }
        $this->currentUser = $this->userQuery->findById($this->getCurrentId());
        return $this->currentUser;
    }

    public function logout() {
        $this->userHashTable->deleteByUserIdAndName($this->getCurrentId(), 'remember');
        $this->response->setCookie('remember_hash', null);
        $this->session->destroy();
    }

    public function register($values) {
        // save the user data
        $fields = ['email', 'first_name', 'last_name', 'name']; // must be extendable by modules! save event maybe?
        $user = $this->userTable->create();
        $user->setAll($values, $fields);
        $user->set('password', $this->hash($values['password']));
        $this->userTable->save($user);
        // create the activation hash
        $hash = $this->hash(time());
        $this->userHashTable->add($user->get('id'), 'activation', $hash);
        return $hash;
    }

    public function sendRegisterEmail($values, $hash) {
        $this->mailer->init();
        $this->mailer->addAddress($values['email']);
        foreach ($values as $name => $value) {
            $this->mailer->set($name, $value);
        }
        $this->mailer->set('hash', $hash);
        return $this->mailer->send(
            text('user', 'registration'),
            ':user/register-email'
        );
    }

    public function activate($hash) {
        $user = $this->findByHash('activation', $hash, false);
        if (!$user) {
            return false;
        }
        $user->set('active', 1);
        $this->userTable->save($user);
        $this->userHashTable->deleteByUserIdAndName($user->get('id'), 'activation');
        return true;
    }

    public function sendForgotEmail($email) {
        $user = $this->userQuery->findOne(null, ['email' => $email]);
        if (!$user) {
            return false;
        }
        $hash = $this->hash(time());
        $this->userHashTable->add($user->get('id'), 'forgot', $hash);
        $this->mailer->init();
        $this->mailer->addAddress($email);
        $this->mailer->set('hash', $hash);
        $this->mailer->set('user', $user);
        $result = $this->mailer->send(
            text('user', 'password_changing'),
            ':user/forgot-email'
        );
        return $result;
    }

    public function changeForgotPassword(Record $user, $password) {
        $user->set('password', $this->hash($password));
        $this->userTable->save($user);
        $this->userHashTable->deleteByUserIdAndName($user->get('id'), 'forgot');
    }

    public function changePassword(Record $user, $password) {
        $user->set('password', $this->hash($password));
    }
    
    public function changeFullName(Record $user, $firstName, $lastName) {
        $user->set('first_name', $firstName);
        $user->set('last_name', $lastName);
    }

    public function changeEmail(Record $user, $email) {
        $hash = $this->hash($email);
        $user->set('new_email', $email);
        $this->userHashTable->add($user->get('id'), 'new_email', $hash);
        return $hash;
    }

    public function sendNewAddressEmail($email, $hash) {
        $this->mailer->init();
        $this->mailer->addAddress($email);
        $this->mailer->set('hash', $hash);
        return $this->mailer->send(
            text('user', 'new_email_address'),
            ':user/new-address-email'
        );
    }

    public function activateNewEmail($id, $hash) {
        $user = $this->findByHash('new_email', $hash);
        if (!$user) {
            return false;
        }
        $user->set('email', $user->get('new_email'));
        $user->set('new_email', null);
        // TODO: transaction
        $this->userTable->save($user);
        $this->userHashTable->deleteByUserIdAndName($user->get('id'), 'new_email');
        //
        return true;
    }

    public function saveCurrentUser() {
        $user = $this->getCurrentUser();
        $this->userTable->save($user);
    }

    public function changeAvatar(Record $user, $srcPath) {
        $this->removeAvatar($user);
        do {
            try {
                $user->set('avatar', bin2hex(random_bytes(16)));
            } catch (\Exception $ignore) {}
        } while ($this->hasAvatar($user));
        $path = $this->getAvatarPath($user);
        $dir = dirname($path);
        if (!file_exists($dir)) {
            mkdir($dir, 0755, true);
        }
        $srcSize = getimagesize($srcPath);
        $src = imagecreatefromjpeg($srcPath);
        $srcX = 0;
        $srcY = 0;
        $srcW = $srcSize[0];
        $srcH = $srcSize[1];
        $destSize = $this->getAvatarSize();
        $dest = imagecreatetruecolor($destSize, $destSize);
        if ($srcW > $srcH) {
            $srcX = ($srcW - $srcH) / 2;
            $srcW = $srcH;
        } else {
            $srcY = ($srcH - $srcW) / 2;
            $srcH = $srcW;
        }
        imagecopyresampled($dest, $src, 0, 0, $srcX, $srcY, $destSize, $destSize, $srcW, $srcH);
        imagejpeg($dest, $path, $this->getAvatarQuality());
        imagedestroy($dest);
        imagedestroy($src);
    }
    
    public function removeAvatar(Record $user) {
        if (!$this->hasAvatar($user)) {
            return;
        }
        unlink($this->getAvatarPath($user));
    }

    public function getAvatarPath(Record $user) {
        $avatar = $user->get('avatar');
        $prefix = $this->getAvatarPrefix($avatar);
        return $this->app->getMediaPath('/avatar/'.$prefix.$avatar.'.jpg');
    }

    private function getAvatarPrefix($avatar) {
        $prefix = '';
        if ($avatar) {
            $prefix = $avatar[0].$avatar[1].'/'.$avatar[2].$avatar[3].'/';
        }
        return $prefix;
    }

    public function hasAvatar(Record $user) {
        return file_exists($this->getAvatarPath($user));
    }

    public function getAvatarUrl(Record $user) {
        if (!$this->hasAvatar($user)) {
            return $this->app->getModuleUrl('User', '/static/default-avatar.png');
        }

        $avatar = $user->get('avatar');
        $prefix = $this->getAvatarPrefix($avatar);
        return $this->app->getMediaUrl('/avatar/'.$prefix.$avatar.'.jpg');
    }


}