<?php

namespace App\Models;

use App\Database;
use App\Controllers\Helper;


class Order {

    private $db;
    private $table = 'orders';

    public function __construct() {
        $this->db = (new Database())->getConnection();
    }

    public function getSingleOrder($data){
      $query = "SELECT * FROM {$this->table} WHERE id = :id";
      $stmt = $this->db->prepare($query);
      $stmt->bindValue(':id', $data['id'], Helper::getParamType($data['id']));
      $stmt->execute();
      return $stmt->fetch(\PDO::FETCH_ASSOC);

    }
}

