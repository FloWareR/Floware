<?php

namespace App\Controllers;

use App\Models\Customer;
use AppControllers\Helper;

class CustomerController {

    private $customerModel;

    public function __construct() {
        $this->customerModel = new Customer();
    }

    public function get($data) {
        $response = $this->customerModel->getAll($data);
    }

    public function getById($data) {
        $response = $this->customerModel->getById($data);
    }
}