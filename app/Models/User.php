<?php

namespace App\Models;

use App\Database;
use App\Controllers\Helper;
use Exception;

class User {

    private $db;
    private $table = 'Users';

    public function __construct() {
        $this->db = (new Database())->getConnection();
    }

    public function get($data){
      try{
        $query = "SELECT * FROM {$this->table} WHERE username = :username";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':username', $data['username'], Helper::getParamType($data['username']));
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC);

      } catch(\PDOException $e){
        return ['error' => $e->getMessage()];
      }
  
    }

    public function create($data){
      try{
        $query = "INSERT INTO {$this->table}(username, password, email, role) 
        VALUES(:username, :password, :email, :role)";
        $stmt = $this->db->prepare($query);
        foreach($data as $key => $value){
        $stmt->bindValue(":$key", $value, Helper::getParamType($value));
        }
        $stmt->execute();
        $newId = $this->db->lastInsertId();
        return ['message' => 'User created', 'id' => $newId];

      } catch (\PDOException $e) {
        return ['error' => $e->getMessage()];
      }

    }

    public function readById($id){
      try{
        $query = "SELECT username, email, role, profile_picture FROM {$this->table} WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC);

      } catch(\PDOException $e){
        return ['error' => $e->getMessage()];
      }
    }

}

