<?php

namespace App\Models;

use App\Database;
use App\Controllers\Helper;


class Customer {

    private $db;
    private $table = 'customers';

    public function __construct() {
        $this->db = (new Database())->getConnection();
    }

    public function getAll($data){
        try {
            $query = "SELECT * FROM {$this->table} ";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->fetch(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            return ['message' => 'Error reading customers: ' . $e->getMessage()];
        }
    }

    public function getById($data){
        try {
            $query = "SELECT * FROM {$this->table} WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $data['id'], Helper::getParamType($data['id']));
            $stmt->execute();
            return $stmt->fetch(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            return ['message' => 'Error reading customer: ' . $e->getMessage()];
        }
    }
}

