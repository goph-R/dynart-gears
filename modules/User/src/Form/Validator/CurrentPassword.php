<?php

namespace Dynart\Gears\Modules\User\src\Form\Validator;

use Dynart\Gears\Modules\User\src\UserService;
use Dynart\Minicore\Form\Validator;
use Dynart\Minicore\Framework;
use Dynart\Minicore\Session;

class CurrentPassword extends Validator {

    /** @var UserService */
    private $userService;
    
    public function __construct() {
        parent::__construct();
        $framework = Framework::instance();
        $this->userService = $framework->get('userService');
        $this->message = text('user', 'current_password_mismatch');
    }

    public function doValidate($value) {
        if ($value && $this->userService->hash($value) != $this->userService->getCurrentUser()->get('password')) {
            return false;
        }
        return true;
    }

}
