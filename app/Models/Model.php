<?php

namespace App\Models;

use App\Controllers\Helper;
use App\TransactionManager;
use Exception;
use PDO;

class Model {

    protected $db;
    protected $table;

    public function __construct($table) {
        $this->db = (TransactionManager::getInstance())->getConnection();
        $this->table = $table;
    }  

    public function readAll() {
        try {
            $query = "SELECT * FROM {$this->table}";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            throw new Exception("Error reading {$this->table}: " . $e->getMessage());
        }
    }

    public function readById($params) {
        try {
            $query = "SELECT * FROM {$this->table} WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $params, Helper::getParamType($params));
            $stmt->execute();
            return $stmt->fetch(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            throw new Exception("Error reading {$this->table}: " . $e->getMessage());
        }
    }


    public function create($params) {
        try {
            $queryParts = [];
            $queryPartsValues = [];
            foreach ($params as $key => $value) {
                $queryParts[] = "$key";
                $queryPartsValues[] = ":$key";
            }
            $query = "INSERT INTO {$this->table} (" . implode(', ', $queryParts) . ") VALUES (" . implode(', ', $queryPartsValues) . ")";
            $stmt = $this->db->prepare($query);
            foreach ($params as $key => $value) {
                $stmt->bindValue(":{$key}", $value, Helper::getParamType($value));
            }
            $stmt->execute();
            $newId = $this->db->lastInsertId();
            return ['message' => "{$this->table} created", 'id' => $newId];
        } catch (\PDOException $e) {
            throw new Exception("Error creating {$this->table}: " . $e->getMessage());
        }
    }

    public function update($params) {
        try {
            $queryParts = [];
            foreach ($params as $key => $value) {
                if (strpos($value, 'quantity -') !== false) {
                    $queryParts[] = "$key = $value";
                } else {
                    $queryParts[] = "$key = :$key";
                }
            }
            $query = "UPDATE {$this->table} SET " . implode(', ', $queryParts) . " WHERE id = :id";
            $stmt = $this->db->prepare($query);
            foreach ($params as $key => $value) {
                if (strpos($value, 'quantity -') === false) {
                    $stmt->bindValue(":{$key}", $value, Helper::getParamType($value));
                }
            }
            $stmt->execute();
            return ['message' => "{$this->table} updated"];
        } catch (\PDOException $e) {
            throw new Exception("Error updating {$this->table}: " . $e->getMessage());
        }
    }

    public function delete($params) {
        try {
            $checkQuery = "SELECT COUNT(*) AS count FROM Order_Items WHERE product_id = :id";
            $checkStmt = $this->db->prepare($checkQuery);
            $checkStmt->bindParam(':id', $params['id'], Helper::getParamType($params['id']));
            $checkStmt->execute();
            $result = $checkStmt->fetch(PDO::FETCH_ASSOC);
    
            if ($result['count'] > 0) {
                return [
                    'message' => "Product already used in orders, cannot delete.",
                    'id' => $params['id'],
                    'status' => 'error'
                ];
            }
    
            $query = "DELETE FROM {$this->table} WHERE id = :id LIMIT 1";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $params['id'], Helper::getParamType($params['id']));
            $stmt->execute();
    
            if ($stmt->rowCount() > 0) {
                return ['message' => "{$this->table} deleted successfully", 'id' => $params['id']];
            } else {
                return ['message' => "No {$this->table} found with the given ID", 'id' => $params['id']];
            }
        } catch (\PDOException $e) {
            throw new Exception("Error deleting {$this->table}: " . $e->getMessage());
        }
    }
    
}
