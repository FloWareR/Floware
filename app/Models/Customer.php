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

    public function add($data){
        try {
            $queryParts = [];
            $queryPartsValues = [];
            foreach($data as $key => $value){
                $queryParts[] = "$key";
                $queryPartsValues[] = ":$key";
            }
            $query = "INSERT INTO {$this->table} ( " . implode(', ', $queryParts) . ") VALUES ( " . implode(', ', $queryPartsValues) . ")";
            $stmt = $this->db->prepare($query);
            foreach($data as $key => $value){
                $stmt->bindValue(":{$key}", $value, Helper::getParamType($value));
            }
            $stmt->execute();
            $newId = $this->db->lastInsertId();
            return ['message' => 'Customer created', 'id' => $newId];
        } catch (\PDOException $e) {
            return ['message' => 'Error creating product: ' . $e->getMessage()];
        }
    }

    public function update($params){
        try {
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
            return ['message' => 'Customer updated'];
        } catch (\PDOException $e) {
            return ['message' => 'Error updating customer: ' . $e->getMessage()];
        }
    }

    public function delete($params){
        try {
            $query = "DELETE FROM {$this->table} WHERE id = :id LIMIT 1";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $params['id'], Helper::getParamType($params['id']));
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                return ['message' => 'Customer deleted successfully', 'id' => $params['id']];
            } else {
                return ['message' => 'No product found with the given ID', 'id' => $params['id']];
            }
        } catch (\PDOException $e) {
            return ['message' => 'Error deleting customer: ' . $e->getMessage()];
        }
    }
}

