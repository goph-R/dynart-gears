<?php

namespace Dynart\Gears\Modules\User\src\Form\Validator;

use Dynart\Gears\Modules\User\src\UserQuery;

use Dynart\Minicore\Database\Record;
use Dynart\Minicore\Form\Validator;
use Dynart\Minicore\Framework;

class EmailExistsExcept extends Validator {

    /** @var UserQuery */
    private $userQuery;

    /** @var Record */
    private $exceptId;

    public function __construct() {
        parent::__construct();
        $framework = Framework::instance();
        $this->userQuery = $framework->get('userQuery');
        $this->message = text('user', 'email_exists');
    }

    public function setExceptId($exceptId) {
        $this->exceptId = $exceptId;
    }

    public function doValidate($value) {
        $user = $this->userQuery->findOne(['id'], [
            'email' => $value,
            'except_id' => $this->exceptId
        ]);
        return $user ? false : true;
    }

}