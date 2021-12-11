<?php

namespace Dynart\Gears\Modules\User\src\Database;

use Dynart\Minicore\Database\Query;

class RoleQuery extends Query {

    protected $table = 'roleTable';

    public function getJoins() {
        $result = parent::getJoins();
        $this->getJoinsByUserId($result);
        return $result;
    }

    private function getJoinsByUserId(array &$result) {
        if (!$this->getOption('by_user_id')) {
            return;
        }
        $result = array_merge($result, [
            'user_role AS ur ON ur.role_id = r.id',
            'user AS u ON u.id = ur.user_id AND u.id = :user_id'
        ]);
        $this->addSqlParams([
            ':user_id' => $this->getOption('by_user_id')
        ]);
    }
}