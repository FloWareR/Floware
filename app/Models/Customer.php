<?php

namespace App\Models;

use App\Database;
use App\Controllers\Helper;


class Customer extends Model{
    
    public function __construct() {
     parent::__construct('customers');
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

}