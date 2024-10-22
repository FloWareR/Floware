<?php

namespace App\Models;

use App\Controllers\Helper;
use \Exception;

class OneTimeCode extends Model{
        
    public function __construct() {
        parent::__construct('One_Time_Codes');
    }
        
    public function read($params){
      try {
        $query = "SELECT * FROM {$this->table} WHERE code = :code";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':code', $params['code'], Helper::getParamType($params['code']));
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC);
      } catch (\PDOException $e) {
          return false;
      }
    }
    
    public function create($params){
      try {
        $query = "INSERT INTO {$this->table} (code) VALUES (:code)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':code', $params['code'], Helper::getParamType($params['code']));
        $stmt->execute();
        return ['message' => "{$this->table} created", 'code' => $params['code'] ];
      } catch (\PDOException $e) {
          throw new Exception("Error creating {$this->table}: " . $e->getMessage());
      }
    }
  

    public function update($params){
      return parent::update($params);
    }
}

