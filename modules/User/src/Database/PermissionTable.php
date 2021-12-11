<?php

namespace Dynart\Gears\Modules\User\src\Database;

use Dynart\Minicore\Database\Table;

class PermissionTable extends Table {

    protected $name = 'permission';
    protected $translationTable = 'permissionTextTable';
    protected $fields = [
        'id' => null
    ];

}