<?php

namespace Dynart\Gears\Modules\User\src\Controller;

use Dynart\Gears\Modules\User\src\UserService;

use Dynart\Minicore\Controller;
use Dynart\Minicore\Form\Form;
use Dynart\Minicore\Framework;

class Login extends Controller {

    /** @var UserService */
    protected $userService;

    /** @var Form */
    protected $loginForm;

    public function __construct() {
        parent::__construct();
        $framework = Framework::instance();
        $this->userService = $framework->get('userService');
        $this->loginForm = $framework->get('userLoginForm');
    }

    public function index() {
        if ($this->userService->isLoggedIn()) {
            $this->redirect($this->userService->getLoggedInUrl());
        }
        if ($this->loginForm->process()) {
            $email = $this->loginForm->getValue('email');
            $password = $this->loginForm->getValue('password');
            $remember = $this->loginForm->getValue('remember');
            if ($this->userService->login($email, $password, $remember)) {
                //$redirectUrl = null; //$this->userService->getLoginRedirectUrl();
                //$this->userService->setLoginRedirectUrl(null);
                $this->redirect(/*$redirectUrl ? $redirectUrl : */$this->userService->getLoggedInUrl());
            } else {
                $this->loginForm->addError(text('user', 'email_password_not_found'));
            }
        }
        $this->loginForm->setValue('password', ''); // security reasons
        $this->render(':user/login', ['form' => $this->loginForm]);
    }

}