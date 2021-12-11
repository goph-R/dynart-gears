<?php

namespace Dynart\Gears\Modules\User\src\Controller;

use Dynart\Gears\Modules\User\src\UserService;

use Dynart\Minicore\Controller;
use Dynart\Minicore\Database\Record;
use Dynart\Minicore\Form\Form;
use Dynart\Minicore\Framework;

class Settings extends Controller {

    /** @var UserService */
    protected $userService;

    /** @var \Dynart\Gears\Modules\User\src\Form\Settings */
    protected $settingsForm;
    
    protected $saveMessages = [];

    public function __construct() {
        parent::__construct();
        $framework = Framework::instance();
        $this->userService = $framework->get('userService');
        $this->settingsForm = $framework->get('userSettingsForm');
    }

    public function index() {
        $this->userService->requireLogin();
        $form = $this->settingsForm;
        $form->init($this->userService->getCurrentUser());
        $this->processForm($form);
        $form->setValue('old_password', '');
        $form->setValue('password', '');
        $form->setValue('password_again', '');
        $this->view->set(['userService' => $this->userService]);
        $this->render(':user/settings', [
            'form' => $form,
            'active' => 'general',
            'action' => route_url('/settings')
        ]);
    }
    
    public function activate() {
        $hash = $this->request->get('hash');
        if (!$this->userService->isLoggedIn()) {
            $this->redirect('/');
        }
        $user = $this->userService->getCurrentUser();
        if ($this->userService->activateNewEmail($user->get('id'), $hash)) {
            $message = $this->getMessage('info', 'email_activation_successful');
        } else {
            $message = $this->getMessage('error', 'email_activation_not_found');
        }
        $message['title'] = text('user', 'new_email_address');
        $this->render(':user/message', $message);
    }
    
    protected function processForm(Form $form) {
        if (!$form->process()) {
            return;
        }
        $this->save($form);
        if ($this->saveMessages) {
            //$this->userSession->setFlash('settings_messages', $this->saveMessages);
            $this->redirect('/settings');
        }
    }   
    
    public function save(Form $form) {
        $this->saveMessages = [];
        $user = $this->userService->getCurrentUser();
        $save = $this->saveFullName($form, $user);
        $save |= $this->savePassword($form, $user);
        $save |= $this->saveEmail($form, $user);
        if ($save) {
            $this->userService->saveCurrentUser();
        }
    }
    
    protected function saveFullName(Form $form, Record $user) {
        if ($form->getValue('last_name') == $user->get('last_name')
            && $form->getValue('first_name') == $user->get('first_name')) {
            return false;
        }
        $this->userService->changeFullName($user, $form->getValue('first_name'), $form->getValue('last_name'));
        $this->saveMessages[] = $this->getMessage('info', 'fullname_modify_success');
        return true;
    }

    protected function saveEmail(Form $form, Record $user) {
        $email = $form->getValue('email');
        if ($email == $user->get('email')) {
            return false;
        }
        $hash = $this->userService->changeEmail($user, $email);
        if (!$this->userService->sendNewAddressEmail($email, $hash)) {
            $this->saveMessages[] = $this->getMessage('error', 'couldnt_send_email');
            return false;
        }
        $this->saveMessages[] = $this->getMessage('info', 'new_email_was_set');
        return true;
    }
    
    protected function savePassword(Form $form, Record $user) {
        if (!$form->getValue('old_password') || !$form->getValue('password')) {
            return false;
        }
        $this->userService->changePassword($user, $form->getValue('password'));
        $this->saveMessages[] = $this->getMessage('info', 'password_changed');
        return true;
    }
    
    private function getMessage($type, $text) {
        return [
            'type' => $type,
            'text' => text('user', $text)
        ];
    }
    
}
