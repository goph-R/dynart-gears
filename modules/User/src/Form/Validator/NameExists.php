<?php

namespace Dynart\Gears\Modules\User\src\Form\Validator;

use Dynart\Gears\Modules\User\src\UserQuery;
use Dynart\Minicore\Form\Validator;
use Dynart\Minicore\Framework;

class NameExists extends Validator {

    /** @var UserQuery */
    private $userQuery;

    public function __construct() {
        parent::__construct();
        $framework = Framework::instance();
        $this->message = text('user', 'name_exists');
        $this->userQuery = $framework->get('userQuery');
    }

    public function doValidate($value) {
        if ($this->userQuery->findOne(['id'], ['name' => $value])) {
            return false;
        }
        return true;
    }

}
