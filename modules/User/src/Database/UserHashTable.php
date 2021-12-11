<?php

namespace Dynart\Gears\Modules\User\src\Database;

use Dynart\Minicore\Database\Table;

class UserHashTable extends Table {

    protected $name = 'user_hash';
    protected $fields = [
        'id'      => null,
        'user_id' => null,
        'name'    => null,
        'hash'    => null
    ];

    public function deleteByUserIdAndName(int $userId, string $name) {
        $sql = "DELETE FROM `user_hash` WHERE `user_id` = :user_id AND `name` = :name LIMIT 1";
        return $this->db->query($sql, [
            ':user_id' => $userId,
            ':name'    => $name
        ]);
    }

    public function add(int $userId, string $name, string $hash) {
        $record = $this->create();
        $record->set('user_id', $userId);
        $record->set('name', $name);
        $record->set('hash', $hash);
        $this->save($record);
    }
}