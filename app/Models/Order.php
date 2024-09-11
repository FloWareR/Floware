<?php

namespace App\Models;

use App\Database;
use App\Controllers\Helper;


class Order extends Model{

  public function __construct() {
    parent::__construct('orders');
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

}

