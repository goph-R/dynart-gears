<?php

namespace Dynart\Gears\Modules\User\src\Form;

use Dynart\Minicore\Form\Form;

class Forgot extends Form {

    public function __construct(string $name='forgot') {
        parent::__construct($name);
        $emailInput = $this->addInput('email', 'Email',
            ['Text']
        );
        $emailInput->setAttribute('autocomplete', 'off'); // security reasons
        $this->addValidator('email', 'Email');
        $emailExistsValidator = $this->addValidator('email', 'EmailExists');
        $emailExistsValidator->setNeedToExists(true);
        $this->addInput('submit', '',
            ['Submit', text('user', 'send')]
        );

    }

}