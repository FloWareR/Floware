<?php

namespace App\Models;

use App\Controllers\Helper;
use \Exception;


class User extends Model {

    public function __construct() {
      parent::__construct('Users');
    }
    public function read($data){
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

    public function readById($params)
    {
      return parent::readById($params);
    }

    public function create($data){
      unset($data['code']);
      return parent::create($data);
    }

    public function update($data){
      return parent::update($data);
    }

    public function subscribe($params){
      try {
        $query = "UPDATE {$this->table} SET subscribed = :subscribed WHERE id = :id";
        $stmt = $this->db->prepare($query);
        foreach ($params as $key => $value) {
                $stmt->bindValue(":{$key}", $value, Helper::getParamType($value));
        }
        $stmt->execute();
        return ['message' => "{$this->table} updated"];
    } catch (\PDOException $e) {
        throw new Exception("Error updating {$this->table}: " . $e->getMessage());
    }
    }
}

