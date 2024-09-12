<?php

namespace App\Models;

use App\Controllers\Helper;

class Order extends Model{

  public function __construct() {
    parent::__construct('Orders');
  }

  public function getAll(){
    return parent::readAll();
  }

  public function getById($params){
    return parent::readById($params);
  }

  public function add($params){
    return parent::create($params);
  }

  public function update($params){
    return parent::update($params);
  }

  public function delete($params){
    return parent::delete($params);
  }

  // Order Specific Methods

  public function updateStock($params){
    try {
      $queryParts = [];
      foreach($params as $key => $value){
        $queryParts[] = "$key = :$key";
      }
      $query = "UPDATE products SET `quantity`= quantity - :quantity WHERE id = :product_id";
      $stmt = $this->db->prepare($query);
      $stmt->bindValue(':quantity', $params['quantity'], Helper::getParamType($params['quantity']));
      $stmt->bindValue(':product_id', $params['product_id'], Helper::getParamType($params['product_id']));
      $stmt->execute();
      return ['message' => "Stock updated"];
    } catch (\Exception $e) {
      return null;
    }
  }



}
