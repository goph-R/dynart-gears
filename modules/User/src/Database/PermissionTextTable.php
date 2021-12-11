<?php

namespace Dynart\Gears\Modules\User\src\Database;

use Dynart\Minicore\Database\TranslationTable;

class PermissionTextTable extends TranslationTable {

    protected $name = 'permission_text';
    protected $fields = [
        'id'     => null,
        'locale' => null,
        'name'   => null
    ];

}

