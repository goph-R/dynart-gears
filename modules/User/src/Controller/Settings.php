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
        $this->userService->requireLogin('/user-settings');
        $this->settingsForm->init($this->userService->getCurrentUser());
        $this->processForm($this->settingsForm);
        $this->settingsForm->setValues([
            'old_password' => '',
            'password' => '',
            'password_again' => ''
        ]);
        $this->render(':user/settings', [
            'form' => $this->settingsForm,
            'active' => 'general',
            'action' => route_url('/user-settings')
        ]);
    }

    public function activate() {
        $hash = $this->request->get('hash');
        $this->userService->requireLogin('/user-settings/activate', ['hash' => $hash]);
        if ($this->userService->activateNewEmail($this->userService->getCurrentId(), $hash)) {
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
        $this->saveMessages = [];
        $user = $this->userService->getCurrentUser();
        $save = $this->saveFullName($form, $user);
        $save |= $this->savePassword($form, $user);
        $save |= $this->saveEmail($form, $user);
        if ($save) {
            $this->userService->saveCurrentUser();
        }
        if ($this->saveMessages) {
            //$this->userSession->setFlash('settings_messages', $this->saveMessages);
            $this->redirect('/settings');
        }
    }   
    
    protected function saveFullName(Form $form, Record $user) {
        if ($form->getValue('last_name') == $user->get('last_name')
            && $form->getValue('first_name') == $user->get('first_name')) {
            return false;
        }
        $user->setAll([
            'first_name' => $form->getValue('first_name'),
            'last_name' => $form->getValue('last_name')
        ]);
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
    
    protected function getMessage($type, $text) {
        return [
            'type' => $type,
            'text' => text('user', $text)
        ];
    }
    
}
