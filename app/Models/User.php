<?php

namespace App\Models;

use App\Database;
use App\Controllers\Helper;

class User {

    private $db;
    private $table = 'users';

    public function __construct() {
        $this->db = (new Database())->getConnection();
    }

    public function getUserData($data){
      $query = "SELECT * FROM {$this->table} WHERE username = :username";
      $stmt = $this->db->prepare($query);
      $stmt->bindValue(':username', $data['username'], Helper::getParamType($data['username']));
      $stmt->execute();
      return $stmt->fetch(\PDO::FETCH_ASSOC);

    }
}

