<?php

namespace App\Models;

 use App\Controllers\Helper;

class OrderItems extends Model{

  public function __construct() {
    parent::__construct('Order_Items');
  }

  public function add($params){
    return parent::create($params);
  }
}