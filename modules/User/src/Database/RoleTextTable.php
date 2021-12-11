<?php

namespace Dynart\Gears\Modules\User\src\Database;

use Dynart\Minicore\Database\TranslationTable;

class RoleTextTable extends TranslationTable {

    protected $name = 'role_text';
    protected $fields = [
        'id'     => null,
        'locale' => null,
        'name'   => null
    ];

}

