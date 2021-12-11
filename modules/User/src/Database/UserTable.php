<?php

namespace Dynart\Gears\Modules\User\src\Database;

use Dynart\Minicore\Database\Table;

class UserTable extends Table {

    protected $name = 'user';
    protected $fields = [
        'id'         => null,
        'email'      => null,
        'password'   => null,
        'name'       => null,
        'first_name' => null,
        'last_name'  => null,
        'active'     => ['default_value' => 0],
        'last_login' => ['default_value' => null],
        'new_email'  => ['default_value' => ''],
        'avatar'     => ['default_value' => '']
    ];

}