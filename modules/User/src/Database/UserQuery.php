<?php

namespace Dynart\Gears\Modules\User\src\Database;

use Dynart\Gears\Modules\User\src\UserModule;

use Dynart\Minicore\Database\Query;
use Dynart\Minicore\Framework;
use Dynart\Minicore\FrameworkException;

class UserQuery extends Query {

    private $salt;

    protected $table = 'userTable';

    public function __construct(string $database='database') {
        parent::__construct($database);
        $this->initSalt();

        $this->addSelectOption('active', function() {
            return $this->optionEquals('active');
        });

        $this->addSelectOption('email', function() {
            return $this->optionEquals('email');
        });

        $this->addSelectOption('password', function () {
            $password = $this->hash($this->getOption('password'));
            $this->addSqlParams([':password' => $password]);
            return ['password = :password'];
        });

        $this->addSelectOption('except_id', function () {
            $this->addSqlParams([':except_id' => $this->getOption('except_id')]);
            return ['id <> :except_id'];
        });
    }

    private function initSalt() {
        $framework = Framework::instance();
        $config = $framework->get('config');
        $this->salt = $config->get(UserModule::CONFIG_SALT);
        if (!$this->salt) {
            throw new FrameworkException("'".UserModule::CONFIG_SALT."' has no value in configuration.");
        }
    }

    public function hash($value) {
        return md5($this->salt.$value);
    }

}