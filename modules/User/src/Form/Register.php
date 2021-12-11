<?php

namespace Dynart\Gears\Modules\User\src\Form;

use Dynart\Minicore\Form\Form;

class Register extends Form {

    public function __construct(string $name='register') {
        parent::__construct($name);
        $this->addInput('name', text('user', 'name'),
            ['Text'],
            text('user', 'will_be_used_as_public')
        );
        $this->addValidator('name', 'NameExists');
        $this->addInput('first_name', text('user', 'first_name'),
            ['Text']
        );
        $this->addInput('last_name', text('user', 'last_name'),
            ['Text']
        );
        $this->addInput('email', 'Email',
            ['Text'],
            text('user', 'we_will_send_an_activation')
        );
        $this->addValidator('email', 'Email');
        $this->addValidator('email', 'EmailExists');
        $this->addInput('password', text('user', 'password'),
            ['Password']
        );
        $this->addValidator('password', 'Password');
        $this->addInput('password_again', text('user', 'password_again'),
            ['Password']
        );
        $same = $this->addValidator('password_again', 'Same');
        $same->setOtherInput($this, 'password');
        $this->addInput('submit', '',
            ['Submit', text('user', 'registration')]
        );        
    }

}