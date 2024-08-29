<?php

namespace App\Models;

use App\Database;
use App\Controllers\Helper;
use PHPUnit\TextUI\Help;

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
        $stmt->bindParam(':id', $params, Helper::getParamType($params));
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
        $newId = $this->db->lastInsertId();
        return ['message' => 'Product created', 'id' => $newId];
    }

    public function update($params){
        $queryParts = [];
        foreach($params as $key => $value){
            $queryParts[] = "$key = :$key";
        }
        $query = "UPDATE {$this->table} SET " . implode(', ', $queryParts) . " WHERE id = :id";
        $stmt = $this->db->prepare($query);
        foreach ($params as $key => $value) {
            $stmt->bindValue(":{$key}", $value, Helper::getParamType($value));
        }       
        $stmt->execute();    
        return ['message' => 'Product updated'];
    }

    public function delete($params){
        $query = "DELETE FROM {$this->table} WHERE id = :id LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $params['id'], Helper::getParamType($params['id']));
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            return ['message' => 'Product deleted successfully', 'id' => $params['id']];
        } else {
            return ['message' => 'No product found with the given ID', 'id' => $params['id']];
        };
    }
}

