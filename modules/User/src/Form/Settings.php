<?php

namespace Dynart\Gears\Modules\User\src\Form;

use Dynart\Minicore\Database\Record;
use Dynart\Minicore\Form\Form;

class Settings extends Form {

    public function __construct(string $name='settings') {
        parent::__construct($name);
        $nameInput = $this->addInput('name', text('user', 'name'),
            ['Text'],
            text('user', 'cant_modify')
        );
        $nameInput->setReadOnly(true);
        $this->setRequired('name', false);
        $this->addInput('first_name', text('user', 'first_name'),
            ['Text']
        );
        $this->addInput('last_name', text('user', 'last_name'),
            ['Text']
        );
        $emailInput = $this->addInput('email', 'Email',
            ['Text']
        );
        $emailInput->setAttribute('autocomplete', 'off'); // security reasons
        $this->addValidator('email', 'Email');
        $this->addInput('old_password', text('user', 'old_password'),
            ['Password'],
            text('user', 'set_if_change_password')
        );
        $this->addValidator('old_password', 'CurrentPassword');
        $this->setRequired('old_password', false);
        $this->addInput('password', text('user', 'new_password'),
            ['Password']);
        $this->addValidator('password', 'Password');
        $same = $this->addValidator('password', 'Same');
        $same->setOtherInput($this, 'password_again');
        $this->setRequired('password', false);
        $this->addInput('password_again', text('user', 'new_password_again'),
            ['Password']
        );
        $same = $this->addValidator('password_again', 'Same');
        $same->setOtherInput($this, 'password');
        $this->setRequired('password_again', false);
        $this->addInput('submit', '',
            ['Submit', text('user', 'save_settings')]
        );
    }

    public function init(Record $user, $useEmailDescription=true) {
        $fields = ['name', 'first_name', 'last_name', 'email'];
        foreach ($fields as $field) {
            $this->setValue($field, $user->get($field));
        }
        $emailInput = $this->getInput('email');
        $emailInput->setDescription($this->getEmailDescription($user, $useEmailDescription));
        $emailExistsExcept = $this->addValidator('email', 'EmailExistsExcept');
        $emailExistsExcept->setExceptId($user->get('id'));
    }

    protected function getEmailDescription(Record $user, $useEmailDescription) {
        if (!$useEmailDescription) {
            return '';
        }
        $newEmail = $user->get('new_email');
        if (!$newEmail) {
            $result = text('user', 'email_change_description');
        } else {
            $result = text('user', 'waits_for_activation', ['email' => $newEmail]);
        }
        return $result;
    }

}