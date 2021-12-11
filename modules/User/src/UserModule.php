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

    const DEFAULT_AVATAR_SIZE = 512;
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
            'roleTable'           => 'Database\RoleTable',
            'roleTextTable'       => 'Database\RoleTextTable',
            'roleQuery'           => 'Database\RoleQuery',
            'permissionTable'     => 'Database\PermissionTable',
            'permissionTextTable' => 'Database\PermissionTextTable',
            'permissionQuery'     => 'Database\PermissionQuery',

            // service
            'userService'         => 'UserService',

            // forms
            'userRegisterForm'    => 'Form\Register',
            'userLoginForm'       => 'Form\Login',
            'userForgotForm'      => 'Form\Forgot',
            'userNewPasswordForm' => 'Form\NewPassword',
            'userSettingsForm'    => 'Form\Settings',
            'userAvatarForm'      => 'Form\Avatar',

            // controllers
            'userRegisterController' => 'Controller\Register',
            'userLoginController'    => 'Controller\Login',
            'userForgotController'   => 'Controller\Forgot',
            'userLogoutController'   => 'Controller\Logout',
            'userProfileController'  => 'Controller\Profile',
            'userSettingsController' => 'Controller\Settings',
            'userAvatarController'   => 'Controller\Avatar'
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

        /** @var Translation $translation */
        $translation = $this->framework->get('translation');
        $translation->add('user', $this->getFolder().'/translations');

        /** @var Router $router */
        $router = $this->framework->get('router');
        $router->add([
            ['/register', 'userRegisterController', 'index', ['GET', 'POST']],
            ['/register/activation', 'userRegisterController', 'activation'],
            ['/register/activate', 'userRegisterController', 'activate'],
            ['/register/success', 'userRegisterController', 'success'],
            ['/login', 'userLoginController', 'index', ['GET', 'POST']],
            ['/forgot', 'userForgotController', 'index', ['GET', 'POST']],
            ['/forgot/sent', 'userForgotController', 'sent'],
            ['/forgot/new', 'userForgotController', 'newPassword', ['GET', 'POST']],
            ['/forgot/success', 'userForgotController', 'success'],
            ['/logout', 'userLogoutController', 'index'],
            ['/profile/?', 'userProfileController', 'index'],
            ['/settings', 'userSettingsController', 'index', ['GET', 'POST']],
            ['/settings/activate', 'userSettingsController', 'activate'],
            ['/settings/avatar', 'userAvatarController', 'index', ['GET', 'POST']],
            ['/settings/remove-avatar', 'userAvatarController', 'remove'],
        ]);
    }

}