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
                      order_items.product_id, 
                      products.name, 
                      order_items.quantity, 
                      order_items.price, 
                      order_items.total
                    FROM  {$this->table}
                    JOIN Products ON products.id = order_items.product_id
                    WHERE order_items.order_id = :order_id;";
      $stmt = $this->db->prepare($query);
      $stmt->bindParam(':order_id', $params, Helper::getParamType($params));
      $stmt->execute();
      return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    } catch (\PDOException $e) {
      throw new Exception("Error reading {$this->table}: " . $e->getMessage());
    }
  }
}