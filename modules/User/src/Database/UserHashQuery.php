<?php

namespace Dynart\Gears\Modules\User\src\Database;

use Dynart\Minicore\Database\Query;

class UserHashQuery extends Query {

    protected $table = 'userHashTable';

    public function __construct(string $database='database') {
        parent::__construct($database);

        $this->addSelectOption('user_id', function() {
            return $this->optionEquals('user_id');
        });

        $this->addSelectOption('name', function() {
            return $this->optionEquals('name');
        });

        $this->addSelectOption('hash', function() {
            return $this->optionEquals('hash');
        });
    }
}