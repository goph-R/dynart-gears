<?php

namespace Dynart\Gears\Modules\User\src;

use Dynart\Minicore\Form\InputTypes;
use Dynart\Minicore\Form\ValidatorTypes;
use Dynart\Minicore\View;
use Dynart\Minicore\Router;
use Dynart\Minicore\Translation;

use Dynart\Gears\Module;

class UserModule extends Module {

    const NAMESPACE = '\Dynart\Gears\Modules\User\src';

    const CONFIG_SALT = 'user.salt';
    const CONFIG_USERS_REGISTER_DISABLED = 'user.register_disabled';
    const CONFIG_LOGGED_IN_URL = 'user.logged_in_url';
    const CONFIG_LOGGED_OUT_URL = 'user.logged_out_url';
    const CONFIG_AVATAR_SIZE = 'user.avatar_size';
    const CONFIG_AVATAR_MAX_FILE_SIZE = 'user.avatar_max_file_size';
    const CONFIG_AVATAR_QUALITY = 'user.avatar_quality';

    const DEFAULT_AVATAR_SIZE = 128;
    const DEFAULT_AVATAR_MAX_FILE_SIZE = 2*1024*1024; // 2MB
    const DEFAULT_AVATAR_QUALITY = 90;

    protected $name = 'User';

    public function __construct() {
        parent::__construct();
        $this->framework->add([

            // database
            'userTable'           => 'Database\UserTable',
            'userQuery'           => 'Database\UserQuery',
            'userHashTable'       => 'Database\UserHashTable',
            'userHashQuery'       => 'Database\UserHashQuery',
            //'roleTable'           => 'Database\RoleTable',
            //'roleTextTable'       => 'Database\RoleTextTable',
            //'roleQuery'           => 'Database\RoleQuery',
            //'permissionTable'     => 'Database\PermissionTable',
            //'permissionTextTable' => 'Database\PermissionTextTable',
            //'permissionQuery'     => 'Database\PermissionQuery',

            // services
            'userService'   => 'UserService',
            'avatarService' => 'AvatarService',

            // forms
            'registerForm'     => 'Form\Register',
            'loginForm'        => 'Form\Login',
            'forgotForm'       => 'Form\Forgot',
            'newPasswordForm'  => 'Form\NewPassword',
            'userSettingsForm' => 'Form\Settings',
            'avatarForm'       => 'Form\Avatar',

            // controllers
            'registerController'     => 'Controller\Register',
            'loginController'        => 'Controller\Login',
            'forgotController'       => 'Controller\Forgot',
            'logoutController'       => 'Controller\Logout',
            'profileController'      => 'Controller\Profile',
            'userSettingsController' => 'Controller\Settings',
            'avatarController'       => 'Controller\Avatar'
        ],
            self::NAMESPACE
        );
    }

    public function init() {

        /** @var InputTypes $inputTypes */
        $inputTypes = $this->framework->get('inputTypes');
        $inputTypes->add([
            'CurrentAvatar' => 'Form\Input\CurrentAvatar'
        ],
            self::NAMESPACE
        );

        /** @var ValidatorTypes $validatorTypes */
        $validatorTypes = $this->framework->get('validatorTypes');
        $validatorTypes->add([
            'CurrentPassword'   => 'Form\Validator\CurrentPassword',
            'EmailExists'       => 'Form\Validator\EmailExists',
            'EmailExistsExcept' => 'Form\Validator\EmailExistsExcept',
            'NameExists'        => 'Form\Validator\NameExists',
            'Same'              => 'Form\Validator\Same',
        ],
            self::NAMESPACE
        );

        /** @var View $view */
        $view = $this->framework->get('view');
        $view->addFolder(':user', $this->getFolder().'/templates');
        $view->changePath(':user/login-layout', ':user/layout');
        $userService = $this->framework->get('userService');
        $view->set([
            'userService' => $userService,
            'avatarService' => $this->framework->get('avatarService'),
            'currentUser' => $userService->getCurrentUser()
        ]);

        /** @var Translation $translation */
        $translation = $this->framework->get('translation');
        $translation->add('user', $this->getFolder().'/translations');

        /** @var Router $router */
        $router = $this->framework->get('router');
        $router->add([
            ['/register', 'registerController', 'index', ['GET', 'POST']],
            ['/register/activation', 'registerController', 'activation'],
            ['/register/activate', 'registerController', 'activate'],
            ['/register/success', 'registerController', 'success'],
            ['/login', 'loginController', 'index', ['GET', 'POST']],
            ['/forgot', 'forgotController', 'index', ['GET', 'POST']],
            ['/forgot/sent', 'forgotController', 'sent'],
            ['/forgot/new', 'forgotController', 'newPassword', ['GET', 'POST']],
            ['/forgot/success', 'forgotController', 'success'],
            ['/logout', 'logoutController', 'index'],
            ['/profile/?', 'profileController', 'index'],
            ['/user-settings', 'userSettingsController', 'index', ['GET', 'POST']],
            ['/user-settings/activate', 'userSettingsController', 'activate'],
            ['/user-settings/avatar', 'avatarController', 'index', ['GET', 'POST']],
            ['/user-settings/remove-avatar', 'avatarController', 'remove'],
        ]);
    }

}