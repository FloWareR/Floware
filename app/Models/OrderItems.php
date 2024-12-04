<?php

namespace App\Models;

use App\Controllers\Helper;
use Exception;

class OrderItems extends Model{

  public function __construct() {
    parent::__construct('Order_Items');
  }

  public function add($params){
    return parent::create($params);
  }

  public function readByOrderId($params){
    try {
      $query = "SELECT 
                      {$this->table}.product_id, 
                      Products.name, 
                      {$this->table}.quantity, 
                      {$this->table}.price, 
                      {$this->table}.total
                    FROM  {$this->table}
                    JOIN Products ON Products.id = {$this->table}.product_id
                    WHERE {$this->table}.order_id = :order_id;";

      $stmt = $this->db->prepare($query);
      $stmt->bindParam(':order_id', $params, Helper::getParamType($params));
      $stmt->execute();
      return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    } catch (\PDOException $e) {
      throw new Exception("Error reading {$this->table}: " . $e->getMessage());
    }
  }
}