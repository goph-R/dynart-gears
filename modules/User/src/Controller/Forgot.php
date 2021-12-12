<?php

namespace Dynart\Gears\Modules\User\src\Controller;

use Dynart\Gears\Modules\User\src\UserService;

use Dynart\Minicore\Controller;
use Dynart\Minicore\Form\Form;
use Dynart\Minicore\Framework;

class Forgot extends Controller {

    /** @var UserService */
    protected $userService;

    /** @var Form */
    protected $forgotForm;

    /** @var Form */
    protected $newPasswordForm;

    public function __construct() {
        parent::__construct();
        $framework = Framework::instance();
        $this->userService = $framework->get('userService');
        $this->forgotForm = $framework->get('forgotForm');
        $this->newPasswordForm = $framework->get('newPasswordForm');
    }

    public function index() {
        if ($this->userService->isLoggedIn()) {
            $this->redirect();
        }
        if ($this->forgotForm->process()) {
            if ($this->userService->sendForgotEmail($this->forgotForm->getValue('email'))) {
                $this->redirect('/forgot/sent');
            } else {
                $this->forgotForm->addError(text('user', 'couldnt_send_email'));
            }
        }
        $this->render(':user/forgot', ['form' => $this->forgotForm]);
    }

    public function sent() {
        return $this->message('info', 'password_changing', 'email_sent_with_instructions');
    }

    public function newPassword($hash) {
        if ($this->userService->isLoggedIn()) {
              $this->redirect();
        }
        $user = $this->userService->findByForgotHash($hash);
        if (!$user) {
            return $this->message('error', 'password_changing', 'activation_not_found');
        }
        if ($this->newPasswordForm->process()) {
            $this->userService->changeForgotPassword($user, $this->newPasswordForm->getValue('password'));
            $this->redirect('/forgot/success');
        }
        $this->newPasswordForm->setValue('password', ''); // security reasons
        $this->newPasswordForm->setValue('password_again', ''); // security reasons
        $this->render(':user/forgot-new-password', [
            'hash' => $hash,
            'form' => $this->newPasswordForm
        ]);
    }

    public function success() {
        $this->message('info', 'password_changing', 'password_changed');
    }

    private function message($type, $title, $message) {
        $this->render(':user/message', [
            'title' => text('user', $title),
            'text' => text('user', $message),
            'type' => $type
        ]);
    }

}