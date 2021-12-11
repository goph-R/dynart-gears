<?php

namespace Dynart\Gears;

use Dynart\Minicore\Database\Query;

class ModuleQuery extends Query {

    protected $table = 'moduleTable';

    public function __construct(string $database = 'database') {
        parent::__construct($database);

        $this->addSelectOption('active', function () {
            return $this->optionEquals('active');
        });
    }

}