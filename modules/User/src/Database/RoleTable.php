<?php

namespace Dynart\Gears\Modules\User\src\Database;

use Dynart\Minicore\Database\Table;

class RoleTable extends Table {

    protected $name = 'role';
    protected $translationTable = 'roleTextTable';
    protected $fields = [
        'id' => null
    ];

}