<?php

namespace App\Controllers;

use App\Models\Customer;
use App\Controllers\Helper;

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

    public function add($data) {
        if(!isset($data['first_name']) || !isset($data['last_name']) || !isset($data['email']) || !isset($data['phone_number'])
            || !isset($data['address']) || !isset($data['type']) || !isset($data['company_name']) || !isset($data['payment_method'])) {
            Helper::sendResponse(400, ['error' => 'Missing Data']);
            return;
        }
        $response = $this->customerModel->add($data);
        Helper::sendResponse(201, $response);
    }

    public function update($data) {
        if(!isset($_GET['id']) || !isset($data)) {
            Helper::sendResponse(400, ['error' => 'Customer not found or data missing']);
            return;
        }
        $data['id'] = $_GET['id'];
        $response = $this->customerModel->update($data);
        Helper::sendResponse(200, $response);
    }

    public function delete($data) {
        if(!isset($_GET['id'])) {
            Helper::sendResponse(400, ['error' => 'Customer not found']);
            return;
        }
        $response = $this->customerModel->delete($data);
        Helper::sendResponse(200, $response);
    }
}