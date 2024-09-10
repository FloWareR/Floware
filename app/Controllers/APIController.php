<?php

namespace App\Controllers;

use App\Controllers\Helper;
use App\Controllers\AuthController;
use App\Controllers\ProductController;
use App\Controllers\CustomerController;

class APIController {

    private $authController;
    private $productController;
    private $jwtMiddleware;
    private $customerController;

    #region Products
    public function __construct() {
        $this->authController = new AuthController();
        $this->productController = new productController();
        $this->customerController = new CustomerController();
    }

    public function getProduct($data) {
        if(isset($_GET['id'])) {
            $this->productController->getById($data);
            return;
        }
       $this->productController->getAll($data);
    }

    public function addProduct($data) { 
        $this->productController->add($data);
    }

    public function updateProduct($data) { 
        $this->productController->update($data);
    }

    public function deleteProduct($data) { 
        $this->productController->delete($data);
    }
    #endregion


    #region Users
    public function login($data){
        $this->authController->authenticate($data);
    }

    public function createUser($data){
        $this->authController->create($data);
    }
    #endregion


    #region Clients
    public function getCustomer($data) {
        if(isset($_GET['id'])) {
            $this->customerController->getById($data);
            return;
        }
        $this->customerController->get($data);
    }

    public function addCustomer($data) {
        $this->customerController->add($data);
    }

    public function updateCustomer($data) {
        $this->customerController->update($data);
    }

    public function deleteCustomer($data) {
        $this->customerController->delete($data);
    }
    #endregion



    #region JWT
    public function verifyToken($clearance, $data) {
        if($this->jwtMiddleware->verifyToken($clearance, $data)) {
           return true;
        } else {
            Helper::sendResponse(401, ['message' => 'Unauthorized']);
            return false;
        }
    }
    #endregion
}