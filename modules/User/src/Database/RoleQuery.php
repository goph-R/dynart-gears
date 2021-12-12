<?php

namespace Dynart\Gears\Modules\User\src\Database;

use Dynart\Minicore\Database\Query;

class RoleQuery extends Query {

    protected $table = 'roleTable';

    public function __construct(string $database='database') {
        parent::__construct($database);

        $this->addJoinOption('by_user_id', function() {
            $this->addSqlParams([
                ':by_user_id' => $this->getOption('by_user_id')
            ]);
            return [
                'user_role AS ur ON ur.role_id = r.id',
                'user AS u ON u.id = ur.user_id AND u.id = :by_user_id'
            ];
        });
    }

}