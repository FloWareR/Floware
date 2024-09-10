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
}

