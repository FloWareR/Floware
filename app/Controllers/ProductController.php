<?php

namespace App\Controllers;

use App\Models\Product;

class ProductController extends Controller{

    private $model;

    public function __construct() {
        $this->model = new Product();
        parent::__construct($this->model, 'products');
    }

    public function getAll($data) {
        parent::getAll($data);
    }

    public function getById($data) {
        parent::getById($data);
    }

    public function add($data, $requiredData) {
        parent::add($data, $requiredData);
    }

    public function update($data) {
        parent::update($data);
    }

    public function delete($data) {
        parent::delete($data);
    }


}