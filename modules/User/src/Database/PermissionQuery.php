<?php

namespace Dynart\Gears\Modules\User\src\Database;

use Dynart\Minicore\Database\Query;

class PermissionQuery extends Query {

    protected $table = 'permissionTable';

    public function __construct(string $database='database') {
        parent::__construct($database);

        $this->addJoinOption('by_user_id', function () {
            $this->addSqlParams([
                ':by_user_id' => $this->getOption('by_user_id')
            ]);
            return [
                'role_permission AS rp ON p.id = rp.permission_id',
                'role AS r ON r.id = rp.role_id',
                'user_role AS ur ON ur.role_id = r.id',
                'user AS u ON u.id = ur.user_id AND u.id = :by_user_id'
            ];
        });
    }

}