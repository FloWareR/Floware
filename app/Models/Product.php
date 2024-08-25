<?php

namespace App\Models;

use App\Database;
use App\Controllers\Helper;

class Product {

    private $db;
    private $table = 'products';

    public $id;
    public $name;

    public function __construct() {
        $this->db = (new Database())->getConnection();
    }

    public function readAll(){
        $query = "SELECT * FROM {$this->table}";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function readById($params){
        $query = "SELECT * FROM {$this->table} WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $params['id'], \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function create($params){
        $queryParts = [];
        $queryPartsValues = [];

        foreach($params as $key => $value){
            $queryParts[] = "$key";
            $queryPartsValues[] = ":$key";
        }

        $query = "INSERT INTO {$this->table} ( " . implode(', ', $queryParts) . ") VALUES ( " . implode(', ', $queryPartsValues) . ")";
        $stmt = $this->db->prepare($query);
        
        foreach($params as $key => $value){
            $stmt->bindValue(":{$key}", $value, Helper::getParamType($value));
        }

        $stmt->execute();
        return ['message' => 'Product created'];
    }

    public function update($params){
        $queryParts = [];

        foreach($params as $key => $value){
            $queryParts[] = "$key = :$key";
        }

        $query = "UPDATE {$this->table} SET " . implode(', ', $queryParts) . " WHERE id = :id";
        $stmt = $this->db->prepare($query);

        foreach ($params as $key => $value) {
            if ($key === 'id') {
                $stmt->bindValue(':id', $value, \PDO::PARAM_INT);
            } else {
                $stmt->bindValue(":{$key}", $value, Helper::getParamType($value));
            }
        }       

        $stmt->execute();
        return ['message' => 'Product updated'];
    }
}

