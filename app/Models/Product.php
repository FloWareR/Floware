<?php

namespace App\Models;

use App\Database;
use App\Controllers\Helper;

class Product extends Model{
        
    public function __construct() {
        parent::__construct('products');
    }
        
    public function readAll(){
        return parent::readAll();
    }
        
    public function readById($params){
        return parent::readById($params);
    }
    
    public function create($params){
        return parent::create($params);
    }
    
    public function update($params){
        return parent::update($params);
    }
    
    public function delete($params){
        return parent::delete($params);
    }

    public function readColumn($params){
        try {
            $queryParts = [];
            foreach($params['read_column'] as $key){
                $queryParts[] = "$key";
            }
            $query = "SELECT " . implode(', ', $queryParts) . " FROM {$this->table} WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $params['product_id'], Helper::getParamType($params['product_id']));
            $stmt->execute();
            return $stmt->fetch(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            return ['message' => "Error reading $this->table" . $e->getMessage()];
        }
    }

}

