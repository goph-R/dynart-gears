<?php

namespace Dynart\Gears\Modules\User\src\Form;

use Dynart\Minicore\Form\Form;

class NewPassword extends Form {

    public function __construct(string $name='new_password') {
        parent::__construct($name);
        $this->addInput('password', text('user', 'password'),
            ['Password']
        );
        $this->addValidator('password', 'Password');
        $this->addInput('password_again', text('user', 'password_again'),
            ['Password']
        );
        $same = $this->addValidator('password_again', 'Same');
        $same->setOtherInput($this, 'password');
        $this->addInput('submit','',
            ['Submit', text('user', 'password_changing')]
        );

    }

}