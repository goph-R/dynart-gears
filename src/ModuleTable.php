<?php

namespace Dynart\Gears;

use Dynart\Minicore\Database\Table;

class ModuleTable extends Table {
    protected $name = 'module';
    protected $fields = [
        'id' => null,
        'name' => null,
        'description' => null
    ];
}