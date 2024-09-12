<?php

namespace App\Controllers;

use App\Models\Customer;

class CustomerController extends Controller{

    private $model;

    public function __construct() {
        $this->model = new Customer();
        return parent::__construct($this->model, 'products');
    }

    public function getAll($data) {
        return parent::getAll($data);
    }

    public function getById($data) {
        return parent::getById($data);
    }

    public function add($data, $requiredData) {
        return parent::add($data, $requiredData);
    }

    public function update($data) {
        return parent::update($data);
    }

    public function delete($data) {
        return parent::delete($data);
    }

}