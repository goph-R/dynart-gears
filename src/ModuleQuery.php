<?php

namespace Dynart\Gears;

use Dynart\Minicore\Database\Query;

class ModuleQuery extends Query {

    protected $table = 'moduleTable';

    public function getConditions() {
        $result = parent::getConditions();
        if ($this->getOption('active', false)) {
            $result[] = 'active = 1';
        }
        return $result;
    }
}