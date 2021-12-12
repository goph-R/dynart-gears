<?php

namespace Dynart\Gears\Modules\User\src\Controller;

use Dynart\Minicore\Controller;
use Dynart\Minicore\Framework;
use Dynart\Minicore\Form\Form;

use Dynart\Gears\Modules\User\src\UserService;

class Register extends Controller {

    /** @var UserService */
    protected $userService;

    /** @var Form */
    protected $registerForm;

    public function __construct() {
        parent::__construct();
        $framework = Framework::instance();
        $this->userService = $framework->get('userService');
        if ($this->userService->isRegisterDisabled()) {
            $this->redirect('/');
        }
        $this->registerForm = $framework->get('registerForm');
    }

    public function index() {
        if ($this->userService->isLoggedIn()) {
            $this->redirect('/');
        }
        if ($this->registerForm->process()) {
            $values = $this->registerForm->getValues();
            $hash = $this->userService->register($values);
            if ($this->userService->sendRegisterEmail($values, $hash)) {
                $this->redirect('/register/activation');
            }
            $this->registerForm->addError(text('user', 'couldnt_send_email'));
        }
        $this->registerForm->setValues([ // security reasons
            'password' => '',
            'password_again' => ''
        ]);
        $this->render(':user/register', [
            'form' => $this->registerForm
        ]);
    }

    public function activation() {
        $this->message('info', 'activation', 'activation_sent');
    }

    public function activate() {
        $hash = $this->request->get('hash');
        if ($this->userService->activate($hash)) {
            $this->redirect('/register/success');
        }
        $this->message('error', 'activation', 'activation_unsuccessful');
    }

    public function success() {
        $this->message('info', 'registration', 'registration_successful');
    }

    private function message($type, $title, $message) {
        $this->render(':user/message', [
            'title' => text('user', $title),
            'text' => text('user', $message),
            'type' => $type
        ]);
    }

}