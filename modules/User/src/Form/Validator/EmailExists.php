<?php

namespace Dynart\Gears\Modules\User\src\Form\Validator;

use Dynart\Gears\Modules\User\src\UserQuery;
use Dynart\Minicore\Form\Validator;
use Dynart\Minicore\Framework;

class EmailExists extends Validator {

    /** @var UserQuery */
    private $userQuery;
    private $needToExists;

    public function __construct() {
        parent::__construct();
        $framework = Framework::instance();
        $this->userQuery = $framework->get('userQuery');
        $this->setNeedToExists(false);
    }

    public function setNeedToExists(bool $value) {
        $this->needToExists = $value;
        if ($value) {
            $this->message = text('user', 'email_not_exists');
        } else {
            $this->message = text('user', 'email_exists');
        }
    }

    public function doValidate($value) {
        if ($this->userQuery->findOne(['id'], ['email' => $value])) {
            return $this->needToExists;
        }
        return !$this->needToExists;
    }

}