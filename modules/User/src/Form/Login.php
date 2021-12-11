<?php

namespace Dynart\Gears\Modules\User\src\Form;

use Dynart\Minicore\Form\Form;

class Login extends Form {

    public function __construct(string $name='login') {
        parent::__construct($name);
        $emailInput = $this->addInput('email', 'Email',
            ['Text']
        );
        $emailInput->setAttribute('autocomplete', 'off'); // security reasons
        $this->addInput('password', text('user', 'password'),
            ['Password']
        );
        $this->addInput('remember', '',
            ['Checkbox', '1', text('user', 'remember_me')]
        );
        $this->setRequired('remember', false);
        $this->addInput('submit', '',
            ['Submit', text('user', 'login')]
        );
    }

}